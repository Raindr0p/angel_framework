<?php

  /*
  |--------------------------------------------------------------------------
  | system::debug methods
  |--------------------------------------------------------------------------
  |
  | For Angel developers:
  |
  | system::get_runtime('on');
  |
  | system::get_error('on');
  |
  |--------------------------------------------------------------------------
  |
  | For Angel builders:
  |
  | system::add_error($method_name,$error);
  |
  |
  |
  */

  class system {

    public static $error = [];

    public static $debug = '';

    public static function add_error($method, $code, $error) {
      self::$debug = $method.':'.$code;
      $cry = '<strong>Angel crying:</strong> '.$method.', '.$error.'<br>';
      array_push(self::$error, $cry);
    }

    public static function get_error($switch) {
      if($switch==='on') {
        foreach (self::$error as $cry) {
          echo $cry;
        }
      }
    }

    public static function get_runtime($switch) {
      if($switch==='on') {
        echo '<br>Process Time: ',(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]),'s';
      }
    }

  }
