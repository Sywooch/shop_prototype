<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\UsersEmailsObjectsFactory;
use app\models\UsersEmailsModel;

/**
 * Тестирует класс app\factories\UsersEmailsObjectsFactory
 */
class UsersEmailsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод UsersEmailsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id_users'=>1, 'id_emails'=>'something1@something.com'],
                ['id_users'=>2, 'id_emails'=>'something2@something.com'],
                ['id_users'=>3, 'id_emails'=>'something3@something.com']
            ],
        ]);
        
        $objectsCreator = new UsersEmailsObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(3, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof UsersEmailsModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_users'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_emails'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_users));
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_emails));
    }
}
