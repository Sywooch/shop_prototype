<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\UsersAddressObjectsFactory;
use app\models\UsersAddressModel;

/**
 * Тестирует класс app\factories\UsersAddressObjectsFactory
 */
class UsersAddressObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод UsersAddressObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id_users'=>1, 'id_address'=>1],
                ['id_users'=>2, 'id_address'=>2],
                ['id_users'=>3, 'id_address'=>3]
            ],
        ]);
        
        $objectsCreator = new UsersAddressObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(3, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof UsersAddressModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_users'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_address'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_users));
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_address));
    }
}
