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
    /**
     * Тестирует метод AddressObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>1, 'address'=>'Some Address', 'city'=>'Some city', 'country'=>'Some country', 'postcode'=>'12345'],
                ['id'=>2, 'address'=>'Some Address Next', 'city'=>'Some city Next', 'country'=>'Some country Next', 'postcode'=>'F12345'],
            ],
        ]);
        
        $objectsCreator = new AddressObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(2, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof AddressModel);
        
        //$this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'address'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'city'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'country'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'postcode'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->address));
        $this->assertTrue(isset($mockObject->objectsArray[0]->city));
        $this->assertTrue(isset($mockObject->objectsArray[0]->country));
        $this->assertTrue(isset($mockObject->objectsArray[0]->postcode));
    }
}
