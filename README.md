## Simple for simple projects V
Angel PHP framework is a rapid **PHP+MySQL(MariaDB)** web service builder. Kitchen-sink frameworks require huge personal investments - both in learning and developing. We build Angel for developers who find solutions like Laravel and Yii being overkilled to their projects.


## Philosophy: do more with a flow
**Code block style:**
```PHP
  class::method('trigger_or_input',function($input){
    //your code here
    return $output;
  });
```

**Workflow style:**
```PHP
  class::method_head()->work_1()->work_2()->work_3()->...->end();
```


## Install
- Angel supports PHP 5.4+
- Drag files in
- Done


## **build::get()** a simple get request webpage
Open file **get.php** in folder **./build**
```PHP
<?php
  build::get('simple/demo',function(){
    //your workspace
    view::config([
      'head'=>'demo.head.php',
      'body'=>'demo.body.php',
      'foot'=>'demo.foot.php'
    ]);
    view::push(['hello','world']);
  }); //build::get() method only response to get request
```
Create files **demo.head.php/demo.body.php/demo.foot.php** respectively in folder **head/body/foot** under **./view**. Array('hello','world') has been pushed to all three view files and can be fetch by the variable **$view**.

In **./view/demo.body.php**, write:
```PHP
<?php
  echo $view[0].'#'.$view[1];
  //echos hello#world
?>
```
Now visit http://www.yourweb.com/simple/demo, you can see the result.


## build::post(), jump:: and sql::
Open file **post.php** in folder **./build**
```PHP
<?php
  build::post('route/[a]/[b]',function($a,$b){
    //your workspace
    $out = sql::select('table_name')->where('a=? and b=?',[$a,$b])->order('a')->by('desc')->limit(5);
    jump::to('http://www.google.com');
  }); //build::post() method only response to post request
```
As you can see, Angel supports variables in uri. Remember to pass them in your build:: code block.

**sql::** workflow is SQL command like. You can write them like these:
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
Though remember to config your database connection in build/config.php. For **jump::**, besides **jump::to($url)** method, Angel provides the following as well.
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

## is:: is for logic
```PHP
is::mobile()
//true if user is using a phone

is::empty($array_file_or_str)
//true if []/['','']/''/0 or $_FILES['a_file'] does not exists

is::ary($array);
is::str($str);
is::int($int);
is::float($float);
```
## File system, plugin, cURL and upload
Angel has a standard file system:
```
angel
├- build
|  ├- get.php //build::get() workspace
|  ├- post.php //build::post() workspace
|  └- config.php //configs
|
├- view
|  ├- head
|  ├- body
|  ├- foot
|  └- file //site resources
|
├- file //all upload goes here
|  ├- img //for img file
|  ├- json //for json file
|  └- ... //reserved for future file type
|
└- block //methods live here
   ├- bootstrap.php //autoloader
   ├- core //core methods
   └- plug //plugins
```

Therefor, upload methods only stores file in **./file** folder
```PHP
upload::img($_FILES['a_img'],[
  'prefix'=>'pre_',
  'limit'=>300,
  'quality'=>70
]);
// returns a name (ex. pre_AKdSkDF2s32sa.jpg)
// file stored in ./file/img
// image compressed 70%
// if image is larger than 300KB, return false
// second parameter is optional
```

use cURL to visit an outside resource:
```PHP
curl::get('an_url');
curl::post('an_url', 'post_data_str');
//both return a string
```

If you want to write your own methods, you can drop them in **./plug** folder and:
```PHP
plug::in('your_plugin_name');
```
remember Angel's own code philosophy. Fly with Angel.
