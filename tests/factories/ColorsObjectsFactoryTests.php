<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\ColorsObjectsFactory;
use app\models\ColorsModel;

/**
 * Тестирует класс app\factories\ColorsObjectsFactory
 */
class ColorsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_color = 'gray';
    
    /**
     * Тестирует метод ColorsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>self::$_id, 'color'=>self::$_color],
            ],
        ]);
        
        $objectsCreator = new ColorsObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof ColorsModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'color'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->color));
    }
}
