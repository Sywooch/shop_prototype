<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\UsersObjectsFactory;
use app\models\UsersModel;

/**
 * Тестирует класс app\factories\UsersObjectsFactory
 */
class UsersObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_password = 'password';
    private static $_name = 'name';
    private static $_surname = 'surname';
    private static $_id_emails = 2;
    private static $_id_phones = 3;
    private static $_id_address = 5;
    
    /**
     * Тестирует метод UsersObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>self::$_id, 'password'=>self::$_password, 'name'=>self::$_name, 'surname'=>self::$_surname, 'id_emails'=>self::$_id_emails, 'id_phones'=>self::$_id_phones, 'id_address'=>self::$_id_address],
            ],
        ]);
        
        $objectsCreator = new UsersObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof UsersModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'name'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'surname'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_emails'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_phones'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_address'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->password));
        $this->assertTrue(isset($mockObject->objectsArray[0]->name));
        $this->assertTrue(isset($mockObject->objectsArray[0]->surname));
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_emails));
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_phones));
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_address));
    }
}
