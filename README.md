## Simple for simple projects
Angel PHP framework is a rapid **PHP+MySQL(MariaDB)** web service builder. Kitchen-sink frameworks require huge personal investments - both in learning and developing. We build Angel for developers who find solutions like Laravel and Yii being overkilled to their projects.


## Code philosophy: do more with a flow
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
Create files **demo.head.php/demo.body.php/demo.foot.php** respectively in folder **head/body/foot**. Array('hello','world') has been pushed to all three view files and can be fetch by the variable **$view**.

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
As you can see, Angel supports variables in uri. Remember to pass them in your build:: code block. **sql::** workflow is SQL command like. You can write them like this:
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
