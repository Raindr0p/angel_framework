<?php
  $blocks = array_merge(
    glob('block/core/*.php'),
    glob('block/plug/*.php'),
    glob('build/*.php')
  );
  foreach ($blocks as $filename) {
    require_once $filename;
  }
 //Autoload block & build files

  if($_SERVER['REQUEST_METHOD']==='POST') {
    run::post(
      user::uri()
    );
  }else {
    run::get(
      user::uri()
    );
  }
  //Auto-route

  if(!empty(sql::$connect)){sql::$connect->close();}
  //disconnect SQL
