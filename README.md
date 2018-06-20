# classes
无composer封装的组件

1. 加载 ClassesAutoload.php InitAutoload.php <br/>
    require ("your_realpath/ClassesAutoload.php"); <br/>
    require ("your_realpath/InitAutoload.php"); <br/>
2. 初始化加载函数 <br/>
    $init_autoload = new InitAutoload(); <br/>
    $init_autoload->register(); <br/>
3. 加载需要的类 <br/>
    $download = new \Download\Download();