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
    private static $_id = 1;
    private static $_id_users =45;
    private static $_id_products = 1;
    private static $_id_deliveries = 3;
    private static $_id_payments = 1;
    
    /**
     * Тестирует метод UsersPurchasesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>self::$_id, 'id_users'=>self::$_id_users, 'id_products'=>self::$_id_products, 'id_deliveries'=>self::$_id_deliveries, 'id_payments'=>self::$_id_payments],
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
