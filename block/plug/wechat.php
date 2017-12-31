<?php

  class wechat {

    private static $appid;

    private static $secret;

    private static $token;

    private static $id;

    private static $from;

    private static $re_text = [];

    private static $re_event = [];

    public static function setup(){
      echo user::get('echostr');
    }

    public static function menu($id,$menu){
      self::start($id);
      $at = self::access_token();
      echo curl::post('https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$at,json_encode(['button'=>$menu],JSON_UNESCAPED_UNICODE));
    }

    public static function run(){
      $wx = simplexml_load_string(file_get_contents('php://input'),'SimpleXMLElement',LIBXML_NOCDATA);
      session_start();
      $_SESSION['openid'] = $wx->FromUserName;
      switch(trim($wx->MsgType)){
        case 'text':
          $pattern = trim($wx->Content);
          $list =& self::$re_text;
          break;
        case 'event':
          $list =& self::$re_event;
          $pattern = trim($wx->Event);
          break;
      }
      self::$from = $wx->ToUserName;
      if(array_key_exists($pattern,$list)) {
        call_user_func_array($list[$pattern],[$wx]);
      }
    }

    public static function return($type,$return){
      $time = date('YmdHis');
      switch($type){
        case 'text':
          echo '<xml>
          <ToUserName><![CDATA['.$return['to'].']]></ToUserName>
          <FromUserName><![CDATA['.self::$from.']]></FromUserName>
          <CreateTime>'.$time.'</CreateTime>
          <MsgType><![CDATA[text]]></MsgType>
          <Content><![CDATA['.$return['content'].']]></Content>
          <FuncFlag>0</FuncFlag>
          </xml>';
          break;
        case 'news':
          $count = sizeof($return)-1;
          echo '<xml>
          <ToUserName><![CDATA['.$return['to'].']]></ToUserName>
          <FromUserName><![CDATA['.self::$from.']]></FromUserName>
          <CreateTime>'.$time.'</CreateTime>
          <MsgType><![CDATA[news]]></MsgType>
          <ArticleCount>'.$count.'</ArticleCount>
          <Articles>';
          foreach($return as $i){
            if(is_array($i)){
              echo '<item>
              <Title><![CDATA['.$i['Title'].']]></Title>
              <Description><![CDATA['.$i['Description'].']]></Description>
              <PicUrl><![CDATA['.$i['PicUrl'].']]></PicUrl>
              <Url><![CDATA['.$i['Url'].']]></Url>
              </item>';
            }
          }
          echo '</Articles>
          </xml>';
          break;
      }
    }

    public static function listen($kind,$pattern,$method){
      switch($kind) {
        case 'text':
          $list =& self::$re_text;
          break;
        case 'event':
          $list =& self::$re_event;
          break;
      }
      if(!array_key_exists($pattern,$list)) {
        $list[$pattern] = $method;
      }
    }

    public static function start($id){
      $get = sql::select('wechat')->where('ID=?',[$id])->limit(1)->fetch();
      self::$appid = $get['AppID'];
      self::$secret = $get['AppSecret'];
      self::$token = $get['Token'];
      self::$id = $id;
    }

    public static function register($id,$appid,$secret,$token){
      sql::insert('wechat')->this([
        'ID'=>$id,
        'AppID'=>$appid,
        'AppSecret'=>$secret,
        'Token'=>$token
      ]);
      sql::insert('access_token')->this([
        'Token'=>$token,
        'Updete_Time'=>'0',
        'ID'=>$id
      ]);
    }

    public static function access_token(){
      $at = sql::select('access_token')->where('ID=?',[self::$id])->limit(1)->fetch();
      $update_time = date('YmdHi');
      if(($update_time-$at['Updete_Time'])<139){
        return $at['Token'];
      }else {
        $request = curl::get('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.self::$appid.'&secret='.self::$secret);
        $at = json_decode($request)->access_token;
        sql::update('access_token')->this([
          'Token'=>$at,
          'Updete_Time'=>$update_time
        ])->where('ID=?',[self::$id])->execute();
        return $at;
      }
    }

    public static function get_user_info($access_token,$user_id){
      if(is_array($user_id)){
        $user_id = array_unique($user_id);
      }
      if(is_array($user_id) and !sizeof($user_id)>1){
        $post = ['user_list'=>[]];
        foreach ($user_id as $i) {
          $post['user_list'][] = [
            'openid'=>$i,
            'lang'=>'zh_CN'
          ];
        }
        $postObj = json_decode(curl::post('https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token='.$access_token,json_encode($post,JSON_UNESCAPED_UNICODE)),true)['user_info_list'];
      }else{
        if(is_array($user_id)){
          $user_id = $user_id[0];
        }
        $postObj = json_decode(curl::get('https://api.weixin.qq.com/cgi-bin/user/info?openid='.$user_id.'&access_token='.$access_token),true);
      }
      return $postObj;
    }



    public static function get_qr_ticket($access_token,$in_p){
      $response = curl::post(
        'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token,
        '{"action_name":"QR_LIMIT_STR_SCENE","action_info":{"scene":{"scene_str":"'.$in_p.'"}}}'
      );
      $postObj = json_decode($response);
      return urlencode($postObj->ticket);
    }

    public static function get_qr_img($ticket){
      return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
    }

  }
