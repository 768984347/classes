<?php
/**
 * Created by PhpStorm.
 * User: mxc
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
            return $this->rules[$name]($arguments);
        }
        return true;
    }
}