<?php

namespace app\extensions\twig;

use app\exceptions\ExceptionsTrait;

class ExtTwig extends \Twig_Extension
{
    use ExceptionsTrait;
    
    public function getFunctions()
    {
        try {
            return [
                new \Twig_SimpleFunction('run22', [$this, 'run22']),
            ];
        } catch(Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function run22(string $value)
    {
        try {
            return 'HA-HA-HA! Its ' . $value;
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
