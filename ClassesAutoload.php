<?php
/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-20
 * Time: 16:30
 */

abstract class ClassesAutoload
{
    /**
     * 初始化自动加载函数
     */
    public function register()
    {
        spl_autoload_register([$this, 'newLoader'], false, false);
    }

    /**
     * 自动加载函数
     * @param $class_name
     */
    public function newLoader($class_name)
    {
        $class_name = $this->getClassPath().'/'.$class_name.'.php';
        $class_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
        file_exists($class_name) && require ($class_name);
    }

    /**
     * 抽象方法  //要求使用的类必须实现获取相对于自身root文件夹
     * @return mixed
     */
    abstract function getClassPath();
}
