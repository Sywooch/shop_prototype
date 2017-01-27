<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\RecoverySessionFinder;
use app\models\RecoveryModel;

/**
 * Тестирует класс RecoverySessionFinder
 */
class RecoverySessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства RecoverySessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(RecoverySessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод RecoverySessionFinder::setKey
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetKeyError()
    {
        $key = null;
        
        $widget = new RecoverySessionFinder();
        $widget->setKey($key);
    }
    
    /**
     * Тестирует метод RecoverySessionFinder::setKey
     */
    public function testSetKey()
    {
        $key = 'key';
        
        $widget = new RecoverySessionFinder();
        $widget->setKey($key);
        
        $reflection = new \ReflectionProperty($widget, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод RecoverySessionFinder::find
     * если пуст RecoverySessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $finder = new RecoverySessionFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод RecoverySessionFinder::find
     */
    public function testFind()
    {
        $email = 'some@some.com';
        
        $session = \Yii::$app->session;
        $session->open();
        $session->setFlash('key_test_flash', ['email'=>$email]);
        
        $finder = new RecoverySessionFinder();
        
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'key_test_flash');
        
        $recoveryModel = $finder->find();
        
        $this->assertInstanceOf(RecoveryModel::class, $recoveryModel);
    }
}
