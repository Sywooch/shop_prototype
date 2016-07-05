<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\RulesObjectsFactory;
use app\models\RulesModel;

/**
 * Тестирует класс app\factories\RulesObjectsFactory
 */
class RulesObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_rule = 'some';
    
    /**
     * Тестирует метод RulesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>self::$_id, 'rule'=>self::$_rule],
            ],
        ]);
        
        $objectsCreator = new RulesObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof RulesModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'rule'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->rule));
    }
}
