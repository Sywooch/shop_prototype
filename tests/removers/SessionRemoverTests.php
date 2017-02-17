<?php

namespace app\tests\removers;

use PHPUnit\Framework\TestCase;
use app\removers\SessionRemover;

/**
 * Тестирует класс SessionRemover
 */
class SessionRemoverTests extends TestCase
{
    private $remover;
    
    /**
     * Тестирует свойства SessionRemover
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SessionRemover::class);
        
        $this->assertTrue($reflection->hasProperty('keys'));
    }
    
    public function setUp()
    {
        $this->remover = new SessionRemover();
    }
    
    /**
     * Тестирует метод SessionRemover::setKeys
     */
    public function testSetKeys()
    {
        $keys = [1, 14];
        
        $this->remover->setKeys($keys);
        
        $reflection = new \ReflectionProperty($this->remover, 'keys');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->remover);
        
        $this->assertInternalType('array', $result);
        $this->assertSame($keys, $result);
    }
    
    /**
     * Тестирует метод SessionRemover::remove
     * если пуст SessionRemover::keys
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: keys
     */
    public function testRemoveEmptyKeys()
    {
        $this->remover->remove();
    }
    
    /**
     * Тестирует метод SessionRemover::remove
     */
    public function testRemove()
    {
        $key = 'test_key';
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, 'some Data');
        
        $result = $session->get($key);
        
        $this->assertSame('some Data', $result);
        
        $reflection = new \ReflectionProperty($this->remover, 'keys');
        $reflection->setAccessible(true);
        $reflection->setValue($this->remover, [$key]);
        
        $this->remover->remove();
        
        $result = $session->has($key);
        
        $this->assertFalse($result);
        
        $session->close();
    }
}
