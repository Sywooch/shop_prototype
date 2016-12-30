<?php

namespace app\tests\registry;

use PHPUnit\Framework\TestCase;
use app\registry\Registry;
use app\tests\MockClass;

/**
 * Тестирует класс Registry
 */
class RegistryTests extends TestCase
{
    /**
     * Тестирует свойства Registry
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(Registry::class);
        
        $this->assertTrue($reflection->hasProperty('items'));
    }
    
    /**
     * Тестирует метод Registry::getKey
     * если вызван с неверным параметром
     * @expectedException TypeError
     */
    public function testGetKeyError()
    {
        $registry = new Registry();
        
        $reflection = new \ReflectionMethod($registry, 'getKey');
        $reflection->setAccessible(true);
        $reflection->invoke($registry, 'some');
    }
    
    /**
     * Тестирует метод Registry::getKey
     * если передан пустой массив
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: params
     */
    public function testGetKeyEmptyParam()
    {
        $registry = new Registry();
        
        $reflection = new \ReflectionMethod($registry, 'getKey');
        $reflection->setAccessible(true);
        $reflection->invoke($registry, []);
    }
    
    /**
     * Тестирует метод Registry::getKey
     */
    public function testGetKey()
    {
        $object = new MockClass();
        $params = ['a'=>1, 'b'=>[1, 2, 3]];
        
        $registry = new Registry();
        
        $reflection = new \ReflectionMethod($registry, 'getKey');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($registry, array_merge([$object], $params));
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод Registry::get
     * если вызван с неверным параметром $class
     * @expectedException TypeError
     */
    public function testGetErrorClass()
    {
        $registry = new Registry();
        $registry->get([], [1]);
    }
    
    /**
     * Тестирует метод Registry::get
     * если вызван с неверным параметром $params
     * @expectedException TypeError
     */
    public function testGetErrorParams()
    {
        $registry = new Registry();
        $registry->get(MockClass::class, 'some');
    }
    
    /**
     * Тестирует метод Registry::get
     * если передан пустой класс
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: class
     */
    public function testGetEmptyClass()
    {
        $registry = new Registry();
        $registry->get('', []);
    }
    
    /**
     * Тестирует метод Registry::get
     * если массив параметров пуст
     */
    public function testGetEmptyParams()
    {
        $registry = new Registry();
        $object_1 = $registry->get(MockClass::class);
        
        $this->assertInstanceOf(MockClass::class, $object_1);
        
        $reflection = new \ReflectionProperty($registry, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($registry);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $object_2 = $registry->get(MockClass::class);
        
        $this->assertSame($object_1, $object_2);
    }
    
    /**
     * Тестирует метод Registry::get
     */
    public function testGet()
    {
        $params = ['a'=>1, 'b'=>[1, 2, 3]];
        
        $registry = new Registry();
        $object_1 = $registry->get(MockClass::class, $params);
        
        $this->assertInstanceOf(MockClass::class, $object_1);
        
        $reflection = new \ReflectionProperty($registry, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($registry);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $object_2 = $registry->get(MockClass::class, $params);
        
        $this->assertSame($object_1, $object_2);
    }
    
    /**
     * Тестирует метод Registry::clean
     */
    public function testClean()
    {
        $params = ['a'=>1, 'b'=>[1, 2, 3]];
        
        $registry = new Registry();
        
        $reflection = new \ReflectionProperty($registry, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($registry, $params);
        
        $reflection = new \ReflectionProperty($registry, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($registry);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $registry->clean();
        
        $reflection = new \ReflectionProperty($registry, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($registry);
        
        $this->assertInternalType('array', $result);
        $this->assertEmpty($result);
    }
}
