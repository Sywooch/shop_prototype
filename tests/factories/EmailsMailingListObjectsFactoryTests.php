<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\EmailsMailingListObjectsFactory;
use app\models\EmailsMailingListModel;

/**
 * Тестирует класс app\factories\EmailsMailingListObjectsFactory
 */
class EmailsMailingListObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id_email = 1;
    private static $_id_mailing_list = 23;
    
    /**
     * Тестирует метод EmailsMailingListObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id_email'=>self::$_id_email, 'id_mailing_list'=>self::$_id_mailing_list],
            ],
        ]);
        
        $objectsCreator = new EmailsMailingListObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof EmailsMailingListModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_email'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_mailing_list'));
        
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_email));
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_mailing_list));
    }
}
