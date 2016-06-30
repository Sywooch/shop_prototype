<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\AddressObjectsFactory;
use app\models\AddressModel;

/**
 * Тестирует класс app\factories\AddressObjectsFactory
 */
class AddressObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_address = 'address';
    private static $_city = 'city';
    private static $_country = 'country';
    private static $_postcode = 'postcode';
    
    /**
     * Тестирует метод AddressObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>self::$_id, 'address'=>self::$_address, 'city'=>self::$_city, 'country'=>self::$_country, 'postcode'=>self::$_postcode],
            ],
        ]);
        
        $objectsCreator = new AddressObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof AddressModel);
        
        //$this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'address'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'city'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'country'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'postcode'));
        
        $this->assertTrue(!empty($mockObject->objectsArray[0]->id));
        $this->assertTrue(!empty($mockObject->objectsArray[0]->address));
        $this->assertTrue(!empty($mockObject->objectsArray[0]->city));
        $this->assertTrue(!empty($mockObject->objectsArray[0]->country));
        $this->assertTrue(!empty($mockObject->objectsArray[0]->postcode));
    }
}
