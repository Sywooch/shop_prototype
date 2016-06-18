<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\SizesObjectsFactory;
use app\models\SizesModel;

/**
 * Тестирует класс app\factories\SizesObjectsFactory
 */
class SizesObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод SizesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>1, 'size'=>'Something 1'],
                ['id'=>2, 'size'=>'Something 2'],
            ],
        ]);
        
        $objectsCreator = new SizesObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(2, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof SizesModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'size'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->size));
    }
}
