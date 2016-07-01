<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\ProductsObjectsFactory;
use app\models\ProductsModel;

/**
 * Тестирует класс app\factories\ProductsObjectsFactory
 */
class ProductsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_date = 'some';
    private static $_code = 'some';
    private static $_name = 'some';
    private static $_description = 'some';
    private static $_price = 14.56;
    private static $_images = 'images/';
    /**
     * Тестирует метод ProductsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>self::$_id, 'date'=>self::$_date, 'code'=>self::$_code, 'name'=>self::$_name, 'description'=>self::$_description, 'price'=>self::$_price, 'images'=>self::$_images],
            ],
        ]);
        
        $objectsCreator = new ProductsObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof ProductsModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'date'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'code'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'name'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'description'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'price'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'images'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->date));
        $this->assertTrue(isset($mockObject->objectsArray[0]->code));
        $this->assertTrue(isset($mockObject->objectsArray[0]->name));
        $this->assertTrue(isset($mockObject->objectsArray[0]->description));
        $this->assertTrue(isset($mockObject->objectsArray[0]->price));
        $this->assertTrue(isset($mockObject->objectsArray[0]->images));
    }
}
