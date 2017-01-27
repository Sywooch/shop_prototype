<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\FiltersSessionFinder;
use app\filters\ProductsFiltersInterface;

/**
 * Тестирует класс FiltersSessionFinder
 */
class FiltersSessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства FiltersSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(FiltersSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод FiltersSessionFinder::setKey
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetKeyError()
    {
        $key = null;
        
        $widget = new FiltersSessionFinder();
        $widget->setKey($key);
    }
    
    /**
     * Тестирует метод FiltersSessionFinder::setKey
     */
    public function testSetKey()
    {
        $key = 'key';
        
        $widget = new FiltersSessionFinder();
        $widget->setKey($key);
        
        $reflection = new \ReflectionProperty($widget, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод FiltersSessionFinder::find
     * если пуст FiltersSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $finder = new FiltersSessionFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод FiltersSessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('key_test', [['sortingField'=>'price', 'sortingType'=>SORT_ASC, 'colors'=>[1, 3], 'sizes'=>[1, 2], 'brands'=>[1]]]);
        
        $finder = new FiltersSessionFinder();
        
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'key_test');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(ProductsFiltersInterface::class, $collection);
        
        $session->remove('key_test');
        $session->close();
    }
}
