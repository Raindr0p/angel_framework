<?php

  /*
  |--------------------------------------------------------------------------
  | plug::request plugins (additional class)
  |--------------------------------------------------------------------------
  |
  | plug::in('name'); //requires a plugin
  |
  |
  |
  */


  class plug {

    public static function in($name){
      $url = './block/plug/'.$name.'.php';
      if(file_exists($url)){
        require_once $url;
      }else{
        system::add_error('plug::in()','fail','plug_in does not exist');
      }
    }

  }
