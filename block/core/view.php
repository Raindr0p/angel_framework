<?php

  /*
  |--------------------------------------------------------------------------
  | view::
  |--------------------------------------------------------------------------
  |
  | Group files to form a single view:
  |
  | view::config([
  |   'head'=>'view.head.php',
  |   'some_folder'=>'view.some.php',
  |   'body'=>'view.body.php'
  | ]);
  |
  | require view files from folder head/some_folder/body in this order
  |
  |--------------------------------------------------------------------------
  |
  | Push array to view:
  |
  | view::push($array);
  |
  |
  |
  */

  class view {

    private static $routes;

    public static function push($view='') {
      $out = '';
      foreach(self::$routes as $key=>$value) {
        $route = 'view/'.$key.'/'.$value;
        if(file_exists($route)){
          ob_start();
          require_once $route;
          $out = $out.ob_get_contents();
          ob_end_clean();
        }else {
          system::add_error('view::push()','view_not_exist','view does not exist in location: <strong>'.$route.'</strong>');
        }
      }
      if(is::mobile()){
        echo str_replace(['<mobile>','</mobile>'],'',preg_replace('/(<desktop>)[\s\S]*(<\/desktop>)/u','',$out));
      }else {
        echo str_replace(['<desktop>','</desktop>'],'',preg_replace('/(<mobile>)[\s\S]*(<\/mobile>)/u','',$out));
      }
    }

    public static function config($config) {
      if(!empty($config)) {
        self::$routes = $config;
      }else {
        system::add_error('view::config()','empty_config','missing view config');
      }
    }

  }
