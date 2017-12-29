<?php

  /*
  |--------------------------------------------------------------------------
  | str::format strings
  |--------------------------------------------------------------------------
  |
  | format to HTML:
  |
  | str::html($str);
  |
  | returns: string
  |
  |--------------------------------------------------------------------------
  |
  | format to utf-8:
  |
  | str::utf8($str);
  |
  | returns: string
  |
  |--------------------------------------------------------------------------
  |
  | return part of a str:
  |
  | str::limit($str);
  |
  | returns: string
  |
  |--------------------------------------------------------------------------
  |
  | return a random str:
  |
  | str::random(10);
  |
  | returns: string
  |
  |--------------------------------------------------------------------------
  |
  | split str to array:
  |
  | str::split('hello world',' ');
  |
  | returns: array = ['hello','world']
  |
  |
  |
  */

  class str {

    public static function html($in_p) {
      $pro=htmlspecialchars($in_p);
      $pro=preg_replace("/(\\r)+(\\n)+/","<br>",$pro);
      $pro=preg_replace("/(<br>){2,}/","<br><br>",$pro);
      $pro=str_replace(" ","&nbsp;",$pro);
      return preg_replace("/(&nbsp;){2,}/","&nbsp;&nbsp;",$pro);
    }

    public static function utf8($in_p) {
      $value = str_replace("<br>","\n",$value);
      return str_replace("&nbsp;"," ",$in_p);
    }

    public static function cut($in_p,$limit) {
      return mb_substr($in_p,0,$limit,'UTF-8');
    }

    public static function random($limit) {
      $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYMZabcdefghijklmnopqrstuvwxyz';
      $string='';
      $max=strlen($characters)-1;
      for($i=0;$i<$limit;$i++){
        $string.=$characters[mt_rand(0,$max)];
      }
      return $string;
    }

    public static function split($str,$flag) {
      return explode($flag,$str);
    }

  }
