<?php
namespace Validator\Lib;

use Closure;

/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-19
 * Time: 16:55
 */

class ValidatorRule
{
    protected $rules = []; //自定义规则
    /**
     * 验证数组元素不能为空或者不存在
     * @param $key
     * @return bool
     */
    public function required($key, $data)
    {
        return isset($data[$key]) && !empty($data[$key]);
    }

    /**
     * 添加自定义规则
     * @param array $rules
     * @return bool
     */
    public function addRule(array $rules)
    {
        $this->rules = array_merge($this->rules, $rules);
        return true;
    }

    /**
     * @param $name
     * @param $arguments
     * @return bool
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        //如果满足自定义规则
        if (isset($this->rules[$name]) && $this->rules[$name] instanceof Closure) {
            return $this->rules[$name]($arguments[0], $arguments[1]);
        }
        return true;
    }

    public function mobile($key, $data)
    {
        $pattern = '/^1[3,4,5,7,8]\d{9}$/';
        return isset($data[$key]) && preg_match($pattern, $data[$key]);
    }

    public function phone($key, $data)
    {
        $pattern = '/^(0[0-9]{2,3}[-]{0,})?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/';
        return $this->mobile($key, $data) || (isset($data[$key]) && preg_match($pattern, $data[$key]));
    }
}