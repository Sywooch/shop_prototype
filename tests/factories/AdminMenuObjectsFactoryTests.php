<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\AdminMenuObjectsFactory;
use app\models\AdminMenuModel;

/**
 * Тестирует класс app\factories\AdminMenuObjectsFactory
 */
class AdminMenuObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_name = 'some name';
    private static $_route = 'some/index';
    
    /**
     * Тестирует метод AdminMenuObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>self::$_id, 'name'=>self::$_name,  'route'=>self::$_route],
            ],
        ]);
        
        $objectsCreator = new AdminMenuObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof AdminMenuModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'name'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'route'));
        
        $this->assertFalse(empty($mockObject->objectsArray[0]->id));
        $this->assertFalse(empty($mockObject->objectsArray[0]->name));
        $this->assertFalse(empty($mockObject->objectsArray[0]->route));
    }
}
