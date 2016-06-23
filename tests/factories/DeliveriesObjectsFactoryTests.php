<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\DeliveriesObjectsFactory;
use app\models\DeliveriesModel;

/**
 * Тестирует класс app\factories\DeliveriesObjectsFactory
 */
class DeliveriesObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод DeliveriesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>1, 'name'=>'Some name', 'description'=>'Some description', 'price'=>12.34],
                ['id'=>2, 'name'=>'Some name', 'description'=>'Some description', 'price'=>312.34],
            ],
        ]);
        
        $objectsCreator = new DeliveriesObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(2, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof DeliveriesModel);
        
        //$this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'name'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'description'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'price'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->name));
        $this->assertTrue(isset($mockObject->objectsArray[0]->description));
        $this->assertTrue(isset($mockObject->objectsArray[0]->price));
    }
}
