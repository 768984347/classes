<?php
namespace Http;

use Http\Lib\RequestDataParse;
/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-27
 * Time: 10:56
 */


class Request
{
    protected $all = [];
    protected $input = [];
    protected $request = [];

    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function isPost()
    {
        return $this->getRequestMethod() === 'POST';
    }

    public function isGet()
    {
        return $this->getRequestMethod() === 'GET';
    }

    public function isPut()
    {
        return $this->getRequestMethod() === 'PUT';
    }

    public function isDelete()
    {
        return $this->getRequestMethod() === 'DELETE';
    }

    public function getInput()
    {
        if (empty($this->input)) {
            $this->input = file_get_contents('php://input') ?: [];
        }
        return $this->input;
    }

    public function getAll()
    {
        if (empty($this->all)) {
            $input = $this->getInput();
            if (is_string($input)) {
                if ($decode_json = json_decode($input, true))
                    $input = $decode_json;
                else {
                    $input = [];
                }
            }
            $this->all = array_merge($_REQUEST, $input) ?: [];
            //如果是空字符串设置值为null
            foreach ($this->all as $key => $value) {
                if ((! is_numeric($value)) && empty($value))
                    $this->all[$key] = null;
            }
        }
        return new RequestDataParse($this->all);
    }

    public function only(array $filter)
    {
        $all = $this->getAll();
        $only = [];
        if (! empty($all)) {
            foreach ($filter as $f) {
                if (array_key_exists($f, $all)) {
                    $only[$f] = $all[$f];
                }
            }
        }
        return new RequestDataParse($only);
    }

    public function isWeiXinClient()
    {
        return (false !== strpos($this->getUserAgent(), 'MicroMessenger'));
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        $all = $this->getAll();
        if (isset($all[$name])) {
            return $all[$name];
        } else {
            return null;
        }
    }
}