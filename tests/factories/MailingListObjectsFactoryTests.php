<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\MailingListObjectsFactory;
use app\models\MailingListModel;

/**
 * Тестирует класс app\factories\MailingListObjectsFactory
 */
class MailingListObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_name = 'some name';
    private static $_description = 'some description';
    
    /**
     * Тестирует метод MailingListObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>self::$_id, 'name'=>self::$_name, 'description'=>self::$_description],
            ],
        ]);
        
        $objectsCreator = new MailingListObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof MailingListModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'name'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'description'));
        
        $this->assertFalse(empty($mockObject->objectsArray[0]->id));
        $this->assertFalse(empty($mockObject->objectsArray[0]->name));
        $this->assertFalse(empty($mockObject->objectsArray[0]->description));
    }
}
