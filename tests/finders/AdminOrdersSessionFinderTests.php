<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\AdminOrdersSessionFinder;
use app\filters\AdminOrdersFiltersInterface;

/**
 * Тестирует класс AdminOrdersSessionFinder
 */
class AdminOrdersSessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства AdminOrdersSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrdersSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод AdminOrdersSessionFinder::find
     * если пуст AdminOrdersSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $finder = new AdminOrdersSessionFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод AdminOrdersSessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('key_test', ['sortingType'=>SORT_ASC, 'status'=>'shipped']);
        
        $finder = new AdminOrdersSessionFinder();
        
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setValue($finder, 'key_test');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(AdminOrdersFiltersInterface::class, $collection);
        
        $session->remove('key_test');
        $session->close();
    }
}
