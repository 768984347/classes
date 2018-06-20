<?php
/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-20
 * Time: 16:30
 */

abstract class ClassesAutoload
{
    public function register()
    {
        spl_autoload_register([$this, 'newLoader'], false, false);
    }

    public function newLoader($class_name)
    {
        $class_name = $this->getClassPath().'/'.$class_name.'.php';
        $class_name = str_replace('\\', '/', $class_name);
        file_exists($class_name) && require ($class_name);
    }

    abstract function getClassPath();
}
