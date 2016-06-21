<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\UsersPhonesObjectsFactory;
use app\models\UsersPhonesModel;

/**
 * Тестирует класс app\factories\UsersPhonesObjectsFactory
 */
class UsersPhonesObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод UsersPhonesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id_users'=>1, 'id_phones'=>1],
                ['id_users'=>2, 'id_phones'=>2],
                ['id_users'=>3, 'id_phones'=>3]
            ],
        ]);
        
        $objectsCreator = new UsersPhonesObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(3, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof UsersPhonesModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_users'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_phones'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_users));
        $this->assertTrue(isset($mockObject->objectsArray[0]->id_phones));
    }
}
