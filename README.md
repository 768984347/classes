# classes
无composer封装的组件

php >= 5.6.3

1. 加载 InitAutoload.php <br/>
    require ("your_realpath/InitAutoload.php"); <br/>
2. 初始化加载函数 <br/>
    $init_autoload = new InitAutoload(); <br/>
    $init_autoload->register(); <br/>
3. 加载需要的类 <br/>
    $download = new \Download\Download();
    
    
##类库
File类 <br/>
<pre><code>
$file = new \File\File('./test.txt');
$file->base_name = 'hello.txt';
$file->file_content = 'this is hello.txt';
$file->save();

$file = new \File\File();
$file->file_dir = '/home/www/';
$file->file_name = 'hello';
$file->file_extension = 'txt';
$file->file_content = 'hello world!';
$file->save();

$file = new \File\File();
$file->save('/home/www/test.txt', 'this is test.txt');

</code></pre>

Download类

<pre><code>
$download = new Download();
$download->setFile('/file_path')->setDownloadFileName('text.txt')->start();

$download = new Download();
$file = file_get_contents('./img/test.jpg');
$download->setFile($file)->setDownloadFileName('text.txt')->start();

//如果是非文件类型
$download = new Download();
echo 123;
$download->setDownloadFileName('test.txt')->start();
</code></pre>

Validate类
<pre><code>
$arr = ['name' => 'ok','age' => 12];
$validate = ['name' => 'required|rule_name'];
$error_message = [
    'name.required' => '名称必填',
    'name.rule_name' => 'xxxx'
];

$validate = new Validator($arr, $validate, $error_message);
//如果验证出错
if ($validate->fails()) {
    $errors = $validate->errors(); //获取错误信息
} else {
    ...
}

添加自定义规则
$validate_rule = new \Validator\Lib\ValidatorRule();

$validate_rule->addRule(['my_rule' => function ($key, $arr) {
    //$key = 'test' $arr是原$arr数组
    if (isset($arr[$key]) && $arr[$key] == 'gg') {
        return true;
    }
    return false;
}]);

$arr = ['test' => 'gg'];
$validate = ['test' => 'my_rule'];
$message = [];

$v = new \Validator\Validator($arr, $validate, $message, function ($obj) use ($validate_rule) {
    $obj->rule_obj = $validate_rule;
});
var_dump($v->fails()); //bool(false) (没有出错)

添加自定义默认错误信息
$validate_rule = new \Validator\Lib\ValidatorRule();

$validate_rule->addRule(['my_rule' => function ($key, $arr) {
    return false;
}]);

$validate_message = new \Validator\Lib\ValidatorMessage();
$validate_message->addMessage(['my_rule' => function ($key) {
    return 'hello';
}]);

$arr = ['test' => 'ok'];
$validate = ['test' => 'my_rule'];
$message = [];

$v = new \Validator\Validator($arr, $validate, $message, function ($obj) use ($validate_rule, $validate_message) {
    $obj->rule_obj = $validate_rule;
    $obj->message_obj = $validate_message;
});

var_dump($v->fails()); //bool(true)

var_dump($v->errors()); 
    array(1) {
        ["test"]=>
            array(1) {
            ["my_rule"]=>
                string(5) "hello"
        }
    }
</code></pre>