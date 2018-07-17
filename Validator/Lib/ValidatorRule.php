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
    protected $data = [];

    public function setValidateData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 验证数组元素不能为空或者不存在
     * @param $key
     * @return bool
     */
    public function required($key)
    {
        return ! empty($this->data[$key]);
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
            return $this->rules[$name]($arguments[0]);
        }
        return true;
    }

    /**
     * 验证手机
     * @param $key
     * @param $data
     * @return bool
     */
    public function mobile($key)
    {
        $pattern = '/^1[3,4,5,7,8]\d{9}$/';
        return isset($this->data[$key]) && preg_match($pattern, $this->data[$key]);
    }

    /**
     * 验证座机
     * @param $key
     * @param $data
     * @return bool
     */
    public function plane($key)
    {
        $pattern = '/^(0[0-9]{2,3}[-]{0,})?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/';
        return isset($this->data[$key]) && preg_match($pattern, $this->data[$key]);
    }

    /**
     * 验证座机或者手机
     * @param $key
     * @param $data
     * @return bool
     */
    public function phone($key)
    {
        return $this->mobile($key) || $this->plane($key);
    }

    /**
     * 判断是否是上传的文件
     * @param $key
     * @return bool
     */
    public function file($key)
    {
        if (is_array($_FILES[$key]['tmp_name'])) {
            foreach ($_FILES[$key]['tmp_name'] as $no => $tmp_name) {
                if (! is_uploaded_file($tmp_name) || $_FILES[$key][$no] !== 0)
                    return false;
            }
        } else {
            return is_uploaded_file($_FILES[$key]['tmp_name']) && $_FILES[$key]['error'] === 0;
        }
        return true;
    }

    protected function jpg($mime)
    {
        return $mime === 'image/jpeg';
    }

    public function extension($key, $extension_array)
    {
        $class_method = get_class_methods(get_class());
        $extension_array = explode($this->getExplodeMethodParamSign(), $extension_array);
        $mime = null;
        if ($this->file($key)) {
            if (is_array($_FILES[$key]['type']))
                $mime = $_FILES[$key]['type'];
            else
                $mime = [$_FILES[$key]['type']];
        } elseif (is_file($this->data[$key])) {
            $mime = [$this];
        }
        foreach ($extension_array as $extension) {
            if (in_array($extension, $class_method) && $mime) {
                foreach ($mime as $file_mime) {
                    if (! call_user_func([$this, $extension], $file_mime))
                        break;
                }
                return true;
            }
        }
        return false;
    }

    protected function getExplodeMethodParamSign()
    {
        return ',';
    }
}
