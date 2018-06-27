<?php
namespace Http\Lib;

use ArrayObject;
/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-27
 * Time: 11:45
 */

class RequestDataParse extends ArrayObject
{
    public function __construct($input = array(), $flags = 0, $iterator_class = "ArrayIterator")
    {
        parent::__construct($input, ArrayObject::ARRAY_AS_PROPS, $iterator_class);
    }

    public function alias(array $alias)
    {
        foreach ($alias as $old_name => $new_name) {
            if ($this->offsetExists($old_name)) {
                $this->offsetSet($new_name, $this->offsetGet($old_name));
                $this->offsetUnset($old_name);
            }
        }
        return $this;
    }

    public function toArray()
    {
        return $this->getArrayCopy();
    }
}