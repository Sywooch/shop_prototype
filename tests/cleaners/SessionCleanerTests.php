<?php

namespace app\tests\cleaners;

use PHPUnit\Framework\TestCase;
use app\cleaners\SessionCleaner;

/**
 * Тестирует класс SessionCleaner
 */
class SessionCleanerTests extends TestCase
{
    /**
     * Тестирует свойства SessionCleaner
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SessionCleaner::class);
        
        $this->assertTrue($reflection->hasProperty('keys'));
    }
    
    /**
     * Тестирует метод SessionCleaner::setKeys
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetKeysError()
    {
        $keys = new class() {};
        
        $cleaner = new SessionCleaner();
        $cleaner->setKeys($keys);
    }
    
    /**
     * Тестирует метод SessionCleaner::setKeys
     */
    public function testSetKeys()
    {
        $keys = [1, 14];
        
        $cleaner = new SessionCleaner();
        $cleaner->setKeys($keys);
        
        $reflection = new \ReflectionProperty($cleaner, 'keys');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($cleaner);
        
        $this->assertInternalType('array', $result);
        $this->assertSame($keys, $result);
    }
    
    /**
     * Тестирует метод SessionCleaner::clean
     * если пуст SessionCleaner::keys
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: keys
     */
    public function testCleanEmptyKeys()
    {
        $cleaner = new SessionCleaner();
        $cleaner->clean();
    }
    
    /**
     * Тестирует метод SessionCleaner::clean
     */
    public function testClean()
    {
        $key = 'test_key';
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, 'some Data');
        
        $result = $session->get($key);
        
        $this->assertSame('some Data', $result);
        
        $cleaner = new SessionCleaner();
        
        $reflection = new \ReflectionProperty($cleaner, 'keys');
        $reflection->setAccessible(true);
        $reflection->setValue($cleaner, [$key]);
        
        $cleaner->clean();
        
        $result = $session->has($key);
        
        $this->assertFalse($result);
        
        $session->close();
    }
}
