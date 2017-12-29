<?php
  build::get('routing_rule/[an_input]',function($an_input){
    view::config([
      'head'=>'demo.head.php',
      'body'=>'demo.body.php',
      'foot'=>'demo.foot.php'
    ]);
    $view = ['demo_push'];
    view::push($view);
  });
