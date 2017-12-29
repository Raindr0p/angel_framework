## Angel PHP framework
A rapid PHP+MySQL(MariaDB) web service builder.

## Simple for simple projects
Kitchen-sink frameworks require huge personal investments - both in learning and developing. We build Angel for developers who find solutions like Laravel and Yii being overkilled to their projects.

## Install
- Angel supports PHP 5.4+
- Drag files in
- Done

## Build:: a simple get request webpage
```PHP
<?php
  //code in build/get.php
  //build::get() method only response to get request

  build::get('simple/demo',function(){
    //your workspace
    view::config([
      'head'=>'demo.head.php',
      'body'=>'demo.body.php',
      'foot'=>'demo.foot.php'
    ]);
    view::push(['hello','world']);
  });
```
Now create files 'demo.head.php'/'demo.body.php'/'demo.foot.php' respectively in folder view/head, view/body, view/foot. Array ['hello','world'] has been pushed to all three view files and can be fetch by the variable $view.
```PHP
<!--in demo.body.php-->
<?=$view[0].'#'.$view[1];?>
```
Now visit http://www.yourweb.com/simple/demo, you can see the result.

## Post, jump:: and sql::
```PHP
<?php
  //code in build/post.php
  //build::post() method only response to post request

  build::post('route/[a]/[b]',function($a,$b){
    //your workspace
    $out = sql::select('table_name')->where('a=? and b=?',[$a,$b])->order('a')->by('desc')->limit(5);
    jump::to('http://www.google.com');
  });
```
As you can see, Angel supports variables in uri. Remember to pass them in your build:: code block. sql:: class is SQL command like. You can write them like this:
```PHP
sql::select('table_name')->where('a=? and b=? or c="1"',[$a,$b])->order('a')->by('desc')->limit(5);
//returns a result array or false if encounters error
sql::delete('table_name')->where('a="hi"')->limit([2,6]);
sql::update('table_name')->this([
  'a'=>$update_data,
  'b'=>$update_data
])->where('a="hi"')->limit([2,6]);
sql::insert('table_name')->this(['data_a','data_b'],['data_c','data_d']);
```
Though remember to config your database connection in build/config.php. For jump::, besides to($url) method, Angel provides the following as well.
```PHP
jump::back(-2); //jump back to history visit in -2
jump::refresh();
```

## Array and string
Angel provides a list of useful array and string operations:
```PHP
//ary::
ary::map($input_array, function($key,$value){
  return $value*3;
}); //returns an array with every item multiplied by 3

ary::filter(input_array, function($key,$value){
  return $value>3;
}); //returns an array with only items larger than 3

ary::merge([1,2,3],[4,5,6]);
//returns [1,2,3,4,5,6]

ary::flat([[1,2,3],[4,5,[6,7,8]]]);
//returns [1,2,3,4,5,6,7,8]

ary::size([1,2,3]);
//returns 3

//str::
str::html('hello world'); //returns 'hello&nbsp;world'

str::utf8('hello&nbsp;world'); //returns 'hello world'

str::random(8); //generates an random str with the length of 8

str::split('hello world',' '); //returns ['hello','world']

str::cut('hello world',7); //returns 'hello w'
```

## cURL and upload
