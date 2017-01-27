<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\AdminOrdersFiltersSessionFinder;
use app\filters\AdminOrdersFiltersInterface;

/**
 * Тестирует класс AdminOrdersFiltersSessionFinder
 */
class AdminOrdersFiltersSessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства AdminOrdersFiltersSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrdersFiltersSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersSessionFinder::setKey
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetKeyError()
    {
        $key = null;
        
        $widget = new AdminOrdersFiltersSessionFinder();
        $widget->setKey($key);
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersSessionFinder::setKey
     */
    public function testSetKey()
    {
        $key = 'key';
        
        $widget = new AdminOrdersFiltersSessionFinder();
        $widget->setKey($key);
        
        $reflection = new \ReflectionProperty($widget, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersSessionFinder::find
     * если пуст AdminOrdersFiltersSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $finder = new AdminOrdersFiltersSessionFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersSessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('key_test', ['sortingType'=>SORT_ASC, 'status'=>'shipped']);
        
        $finder = new AdminOrdersFiltersSessionFinder();
        
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'key_test');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(AdminOrdersFiltersInterface::class, $collection);
        
        $session->remove('key_test');
        $session->close();
    }
}
