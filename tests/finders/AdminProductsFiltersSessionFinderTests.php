<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\AdminProductsFiltersSessionFinder;
use app\filters\AdminProductsFiltersInterface;

/**
 * Тестирует класс AdminProductsFiltersSessionFinder
 */
class AdminProductsFiltersSessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства AdminProductsFiltersSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductsFiltersSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод AdminProductsFiltersSessionFinder::setKey
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetKeyError()
    {
        $key = null;
        
        $widget = new AdminProductsFiltersSessionFinder();
        $widget->setKey($key);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersSessionFinder::setKey
     */
    public function testSetKey()
    {
        $key = 'key';
        
        $widget = new AdminProductsFiltersSessionFinder();
        $widget->setKey($key);
        
        $reflection = new \ReflectionProperty($widget, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersSessionFinder::find
     * если пуст AdminProductsFiltersSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $finder = new AdminProductsFiltersSessionFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод AdminProductsFiltersSessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('key_test', [
            'sortingField'=>'price',
            'sortingType'=>SORT_ASC,
            'colors'=>[1, 3],
            'sizes'=>[1, 2],
            'brands'=>[1],
            'categories'=>[1, 4],
            'subcategory'=>[1],
            'active'=>true
        ]);
        
        $finder = new AdminProductsFiltersSessionFinder();
        
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'key_test');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(AdminProductsFiltersInterface::class, $collection);
        
        $session->remove('key_test');
        $session->close();
    }
}
