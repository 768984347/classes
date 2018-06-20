<?php
/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-20
 * Time: 17:00
 */

class InitAutoload extends ClassesAutoload
{
    public function getClassPath()
    {
        // TODO: Implement getClassPath() method.
        return dirname(__FILE__);
    }
}