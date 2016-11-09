<?php

namespace app\models;

use yii\base\Object;
use app\exceptions\ExceptionsTrait;

class DbRegistry extends Object
{
    use ExceptionsTrait;
    
    private $_registry = [];
    
    public function set($key, $data)
    {
        try {
            if (!array_key_exists($key, $this->_registry)) {
                $this->_registry[$key] = $data;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function get($key)
    {
        try {
            if (array_key_exists($key, $this->_registry)) {
                return $this->_registry[$key];
            }
            
            return null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
