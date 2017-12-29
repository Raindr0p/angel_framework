<?php

  /*
  |--------------------------------------------------------------------------
  | in_array::array operations
  |--------------------------------------------------------------------------
  |
  | map an array:
  |
  | in_array::map($array, function($key,$value){
  |   //your code here
  |   return $str;
  | });
  |
  | returns: array
  |
  |--------------------------------------------------------------------------
  |
  | filter an array:
  |
  | in_array::filter($array, function($key,$value){
  |   //your code here
  |   return true/false;
  | });
  |
  | returns: array
  |
  |--------------------------------------------------------------------------
  |
  | merge arrays:
  |
  | in_array::merge([
  |   $array_1,
  |   $array_2,
  |   $array_3
  | ]);
  |
  | returns: array
  |
  |--------------------------------------------------------------------------
  |
  | flatten an array:
  |
  | in_array::flat($array);
  |
  | returns: array
  |
  |
  |
  */

  class ary {

    public static function map($array, $method) {
      if(is::ary($array)){
        if(gettype($method)==='object'){
          foreach($array as $key=>$value) {
            $array[$key] = call_user_func_array($method,[$key,$value]);
          }
          return $array;
        }else{
          system::add_error('in_array::map()','missing_function','second parameter must be a function object');
        }
      }else{
        system::add_error('in_array::map()','missing_array','first parameter must be an array');
      }
    }

    public static function filter($array, $method) {
      if(is::ary($array)){
        if(gettype($method)==='object'){
          $out = [];
          foreach ($array as $key=>$value) {
            if(call_user_func_array($method,[$key,$value])){
              $out[$key] = $value;
            }
          }
          return $out;
        }else{
          system::add_error('in_array::filter()','missing_function','second parameter must be a function object');
        }
      }else{
        system::add_error('in_array::filter()','missing_array','first parameter must be an array');
      }
    }

    public static function merge($array) {
      if(is::ary($array)){
        return call_user_func_array('array_merge',$array);
      }else{
        system::add_error('in_array::merge()','missing_array','input must be an array');
      }
    }

    public static function flat($array) {
      if(is::ary($array)){
        $out = [];
        foreach($array as $key=>$value) {
          if(is_array($value)){
            $out = array_merge($out,self::flat($value));
          }else{
            $out[] = $value;
          }
        }
        return $out;
      }else{
        system::add_error('in_array::flat()','missing_array','input must be an array');
      }
    }

    public static function size($array) {
      if(is::ary($array)){
        return sizeof($array);
      }else{
        system::add_error('in_array::size()','missing_array','input must be an array');
      }
    }

  }
