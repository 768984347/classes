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
        if (isset($_FILES[$key])) {
            if (is_array($_FILES[$key]['tmp_name'])) {
                foreach ($_FILES[$key]['tmp_name'] as $no => $tmp_name) {
                    if (! is_uploaded_file($tmp_name) || $_FILES[$key][$no] !== 0)
                        return false;
                }
                return true;
            } else {
                return is_uploaded_file($_FILES[$key]['tmp_name']) && $_FILES[$key]['error'] === 0;
            }
        }
        return false;
    }

    /**
     * 验证jpg
     * @param $mime
     * @return bool
     */
    protected function jpg($mime)
    {
        return $mime === 'image/jpeg';
    }

    /**
     * 验证png
     * @param $mime
     * @return bool
     */
    protected function png($mime)
    {
        return $mime === 'image/png';
    }

    /**
     * 普通文件使用的验证方法
     * @param $key
     * @param $extension_str
     * @return bool
     */
    public function extension($key, $extension_str)
    {
        $extension_array = $this->getMethodParam($extension_str);
        $mime = [];
        if (is_string($this->data[$key])) {
            if (is_file($this->data[$key])) {
                $mime = [filetype($this->data[$key])];
            } else {
                $mime = [pathinfo($this->data[$key], PATHINFO_EXTENSION)];
            }
        }
        if (! empty($mime))
            return $this->checkFileType($mime, $extension_array);
        return false;
    }

    /**
     * $_FILES文件上传使用的验证方法
     * @param $key
     * @param $extension_str
     * @return bool
     */
    public function fileExtension($key, $extension_str)
    {
        $extension_array = $this->getMethodParam($extension_str);
        if ($this->file($key)) {
            $mime = [];
            //如果是多文件上传
            if (is_array($_FILES[$key]['type'])) {
                $mime = $_FILES[$key]['type'];
            } else {
                $mime = [$_FILES[$key]['type']];
            }
            return $this->checkFileType($mime, $extension_array);
        }
        return false;
    }

    protected function checkFileType(array $mime, array $extension_array)
    {
        $class_method = get_class_methods(get_class());
        //只要有一个extension命中就通过
        foreach ($extension_array as $extension) {
            //如果是一个方法
            if (in_array($extension, $class_method)) {
                foreach ($mime as $file_mime) {
                    //只要有一个mime没有通过就不通过
                    if (! call_user_func([$this, $extension], $file_mime))
                        break;
                    return true; //只要有一个extension命中就通过
                }
            } else {
                //命中自定义的extension就算通过
                if (in_array($extension, $mime))
                    return true;
            }
        }
        return false;
    }

    /**
     * 获取给方法传参的分割符号
     * @return string
     */
    protected function getExplodeMethodParamSign()
    {
        return ',';
    }

    /**
     * 通过分隔符号获取数组
     * @param $param_str
     * @return array
     */
    protected function getMethodParam($param_str)
    {
        return explode($this->getExplodeMethodParamSign(), $param_str);
    }
}
