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
    /**
     * Тестирует метод RulesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>1, 'rule'=>'Something 1'],
                ['id'=>2, 'rule'=>'Something 2'],
                ['id'=>3, 'rule'=>'Something 3'],
                ['id'=>4, 'rule'=>'Something 4'],
            ],
        ]);
        
        $objectsCreator = new RulesObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(4, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof RulesModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'rule'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->rule));
    }
}
