<?php

  /*
  |--------------------------------------------------------------------------
  | upload::store a file
  |--------------------------------------------------------------------------
  |
  | Store an image:
  |
  | upload::img($_FILES['your_file'],[
  |   'limit' => 300  //in KB
  |   'quality' => 70 //compress to 70%
  |   'prefix' => 'z_'
  | ]);
  |
  | //upload to ./file/img
  | //second parameter is optional
  |
  |
  |
  */

  class upload {

    private static function re_name($pre,$name){
      $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYMZabcdefghijklmnopqrstuvwxyz0123456789';
      $filename=explode(".",$name);
      $string='';
      $max=strlen($characters)-1;
      for($i=0;$i<15;$i++){
        $string.=$characters[mt_rand(0,$max)];
      }
      $filename[0]=$pre.$string;
      return implode(".",$filename);
    }


    private static function upload_img($file,$name,$quality){
      if(strpos(strtolower($file['name']),'png') and !imagepng(imagecreatefrompng($file['tmp_name']),$_SERVER['DOCUMENT_ROOT'].'/file/img/'.$name,$quality)){
        system::add_error('upload::img()','store_png_fail','fail to store png file: '.$file['name'].', please check your file permission');
      }elseif(strpos(strtolower($file['name']),'gif') and !imagegif(imagecreatefromgif($file['tmp_name']),$_SERVER['DOCUMENT_ROOT'].'/file/img/'.$name)){
        system::add_error('upload::img()','store_gif_fail','fail to store gif file: '.$file['name'].', please check your file permission');
      }elseif((strpos(strtolower($file['name']),'jpg') or strpos(strtolower($file['name']),'jpeg')) and !imagejpeg(imagecreatefromjpeg($file['tmp_name']),$_SERVER['DOCUMENT_ROOT'].'/file/img/'.$name,$quality)){
        system::add_error('upload::img()','store_jpeg_fail','fail to store jpeg file: '.$file['name'].', please check your file permission');
      }else{
        system::add_error('upload::img()','not_img','not an image file');
      }
    }


    public static function img($file,$config){
      if(is::empty($file)){
        $prefix = array_key_exists('prefix',$config)?$config['prefix']:'';
        $quality = array_key_exists('quality',$config)?$config['quality']:100;
        $name = self::re_name($prefix,$file['name']);
        if(is::empty($config)){
          if(array_key_exists('limit',$config)){
            if($file['size']<$config['limit']*1024){
              self::upload_img($file,$name,$quality);
            }else{
              system::add_error('upload::img()','over_limit','file larger than '.$config['limit'].'KB');
            }
          }
        }else{
          self::upload_img($file,$name,$quality);
        }
        return $name;
      }else{
        system::add_error('upload::img()','empty','empty file');
      }
    }


  }
