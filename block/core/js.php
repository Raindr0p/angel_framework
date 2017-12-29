<?php

  /*
  |--------------------------------------------------------------------------
  | js::simple js builder
  |--------------------------------------------------------------------------
  |
  | build an alert:
  |
  | js::alert('your alert msg');
  |
  | echos js code
  |
  |
  |
  */

  class js {

    public static function alert($content=''){
      echo "<script>alert('",$content,"')</script>";
    }

  }
