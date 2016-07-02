<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\UsersPurchasesInsertObjectsFactory;
use app\models\UsersPurchasesModel;

/**
 * Тестирует класс app\factories\UsersPurchasesInsertObjectsFactory
 */
class UsersPurchasesInsertObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_id_users =45;
    private static $_id_products = 1;
    private static $_id_deliveries = 3;
    private static $_id_payments = 1;
    
    /**
     * Тестирует метод UsersPurchasesInsertObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id_users'=>self::$_id_users, 'id_products'=>self::$_id_products, 'id_deliveries'=>self::$_id_deliveries, 'id_payments'=>self::$_id_payments],
            ],
        ]);
        
        $objectsCreator = new UsersPurchasesInsertObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof UsersPurchasesModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_users'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_products'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_deliveries'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_payments'));
        
        $this->assertTrue(!empty($mockObject->objectsArray[0]->id_users));
        $this->assertTrue(!empty($mockObject->objectsArray[0]->id_products));
        $this->assertTrue(!empty($mockObject->objectsArray[0]->id_deliveries));
        $this->assertTrue(!empty($mockObject->objectsArray[0]->id_payments));
    }
}
