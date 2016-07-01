<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\EmailsObjectsFactory;
use app\models\EmailsModel;

/**
 * Тестирует класс app\factories\EmailsObjectsFactory
 */
class EmailsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_email = 'something1@something.com';
    
    /**
     * Тестирует метод EmailsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>self::$_id, 'email'=>self::$_email],
            ],
        ]);
        
        $objectsCreator = new EmailsObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof EmailsModel);
        
        //$this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'email'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->email));
    }
}
