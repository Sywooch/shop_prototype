<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductsFiltersSessionFinder;
use app\filters\ProductsFiltersInterface;

/**
 * Тестирует класс ProductsFiltersSessionFinder
 */
class ProductsFiltersSessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства ProductsFiltersSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsFiltersSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ProductsFiltersSessionFinder::setKey
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetKeyError()
    {
        $key = null;
        
        $widget = new ProductsFiltersSessionFinder();
        $widget->setKey($key);
    }
    
    /**
     * Тестирует метод ProductsFiltersSessionFinder::setKey
     */
    public function testSetKey()
    {
        $key = 'key';
        
        $widget = new ProductsFiltersSessionFinder();
        $widget->setKey($key);
        
        $reflection = new \ReflectionProperty($widget, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод ProductsFiltersSessionFinder::find
     * если пуст ProductsFiltersSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $finder = new ProductsFiltersSessionFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод ProductsFiltersSessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('key_test', [['sortingField'=>'price', 'sortingType'=>SORT_ASC, 'colors'=>[1, 3], 'sizes'=>[1, 2], 'brands'=>[1]]]);
        
        $finder = new ProductsFiltersSessionFinder();
        
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'key_test');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(ProductsFiltersInterface::class, $collection);
        
        $session->remove('key_test');
        $session->close();
    }
}
