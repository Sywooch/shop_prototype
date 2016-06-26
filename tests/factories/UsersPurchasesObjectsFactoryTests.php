<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\UsersPurchasesObjectsFactory;
use app\models\UsersPurchasesModel;

/**
 * Тестирует класс app\factories\UsersPurchasesObjectsFactory
 */
class UsersPurchasesObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод UsersPurchasesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>1, 'id_users'=>2, 'id_products'=>3, 'id_deliveries'=>23, 'id_payments'=>19],
            ],
        ]);
        
        $objectsCreator = new UsersPurchasesObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof UsersPurchasesModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_users'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_products'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_deliveries'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_payments'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_users));
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_products));
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_deliveries));
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_payments));
    }
}
