<?php

namespace app\tests\traits;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;

class TestClass
{
    use ExceptionsTrait;
    
    public function run()
    {
        try {
            throw new ErrorException('Что-то пошло не так!');
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function runStatic()
    {
        try {
            throw new ErrorException('Что-то пошло не так!');
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}

/**
 * Тестирует трейт ExceptionsTrait
 */
class ExceptionsTraitTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод ExceptionsTrait::throwException
     * @expectedException ErrorException
     */
    public function testThrowException()
    {
        $object = new TestClass();
        $object->run();
    }
    
    /**
     * Тестирует метод ExceptionsTrait::throwStaticException
     * @expectedException ErrorException
     */
    public function testThrowStaticException()
    {
        $object = new TestClass();
        $object->runStatic();
    }
}
