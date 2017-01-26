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
        $reflection->setValue($finder, 'key_test');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(AdminOrdersFiltersInterface::class, $collection);
        
        $session->remove('key_test');
        $session->close();
    }
}
