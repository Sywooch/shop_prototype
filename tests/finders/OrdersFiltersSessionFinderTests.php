<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\OrdersFiltersSessionFinder;
use app\filters\OrdersFiltersInterface;

/**
 * Тестирует класс OrdersFiltersSessionFinder
 */
class AdminOrdersFiltersSessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства OrdersFiltersSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(OrdersFiltersSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод OrdersFiltersSessionFinder::setKey
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetKeyError()
    {
        $key = null;
        
        $widget = new OrdersFiltersSessionFinder();
        $widget->setKey($key);
    }
    
    /**
     * Тестирует метод OrdersFiltersSessionFinder::setKey
     */
    public function testSetKey()
    {
        $key = 'key';
        
        $widget = new OrdersFiltersSessionFinder();
        $widget->setKey($key);
        
        $reflection = new \ReflectionProperty($widget, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод OrdersFiltersSessionFinder::find
     * если пуст OrdersFiltersSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $finder = new OrdersFiltersSessionFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод OrdersFiltersSessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('key_test', ['sortingType'=>SORT_ASC, 'status'=>'shipped']);
        
        $finder = new OrdersFiltersSessionFinder();
        
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'key_test');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(OrdersFiltersInterface::class, $collection);
        
        $session->remove('key_test');
        $session->close();
    }
}
