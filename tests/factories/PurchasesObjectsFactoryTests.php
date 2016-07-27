<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\PurchasesObjectsFactory;
use app\models\PurchasesModel;

/**
 * Тестирует класс app\factories\PurchasesObjectsFactory
 */
class PurchasesObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_quantity = 1;
    private static $_received = 1;
    private static $_received_date = 1462453595;
    private static $_processed = 1;
    private static $_canceled = 1;
    private static $_shipped = 1;
    
    /**
     * Тестирует метод PurchasesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>self::$_id, 'id_users'=>self::$_id, 'id_products'=>self::$_id, 'quantity'=>self::$_quantity, 'id_colors'=>self::$_id, 'id_sizes'=>self::$_id, 'id_deliveries'=>self::$_id, 'id_payments'=>self::$_id, 'received'=>self::$_received, 'received_date'=>self::$_received_date, 'processed'=>self::$_processed, 'canceled'=>self::$_canceled, 'shipped'=>self::$_shipped],
            ],
        ]);
        
        $objectsCreator = new PurchasesObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertTrue(is_array($mockObject->objectsArray));
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof PurchasesModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_users'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_products'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'quantity'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_colors'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_sizes'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_deliveries'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_payments'));
        
        $this->assertFalse(empty($mockObject->objectsArray[0]->id));
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_users));
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_products));
        $this->assertFalse(empty($mockObject->objectsArray[0]->quantity));
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_colors));
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_sizes));
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_deliveries));
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_payments));
        $this->assertFalse(empty($mockObject->objectsArray[0]->received));
        $this->assertFalse(empty($mockObject->objectsArray[0]->received_date));
        $this->assertFalse(empty($mockObject->objectsArray[0]->processed));
        $this->assertFalse(empty($mockObject->objectsArray[0]->canceled));
        $this->assertFalse(empty($mockObject->objectsArray[0]->shipped));
    }
}
