<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\PhonesObjectsFactory;
use app\models\PhonesModel;

/**
 * Тестирует класс app\factories\PhonesObjectsFactory
 */
class PhonesObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод PhonesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>1, 'phone'=>'0569878974'],
                ['id'=>2, 'phone'=>'+380987899654'],
            ],
        ]);
        
        $objectsCreator = new PhonesObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(2, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof PhonesModel);
        
        //$this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'phone'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->phone));
    }
}
