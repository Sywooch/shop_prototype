<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\PaymentsObjectsFactory;
use app\models\PaymentsModel;

/**
 * Тестирует класс app\factories\PaymentsObjectsFactory
 */
class PaymentsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_description = 'Some description';
    
    /**
     * Тестирует метод PaymentsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>self::$_id, 'name'=>self::$_name, 'description'=>self::$_description],
            ],
        ]);
        
        $objectsCreator = new PaymentsObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof PaymentsModel);
        
        //$this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'name'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'description'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->name));
        $this->assertTrue(isset($mockObject->objectsArray[0]->description));
    }
}
