<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\CurrencyObjectsFactory;
use app\models\CurrencyModel;

/**
 * Тестирует класс app\factories\CurrencyObjectsFactory
 */
class CurrencyObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод CurrencyObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>1, 'currency'=>'Something 1'],
                ['id'=>2, 'currency'=>'Something 2'],
            ],
        ]);
        
        $objectsCreator = new CurrencyObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(2, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof CurrencyModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'currency'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->currency));
    }
}
