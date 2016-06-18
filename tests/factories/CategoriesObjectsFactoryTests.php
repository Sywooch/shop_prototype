<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\CategoriesObjectsFactory;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\factories\CategoriesObjectsFactory
 */
class CategoriesObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод CategoriesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>1, 'name'=>'Something 1'],
                ['id'=>2, 'name'=>'Something 2'],
                ['id'=>3, 'name'=>'Something 3'],
                ['id'=>4, 'name'=>'Something 4']
            ],
        ]);
        
        $objectsCreator = new CategoriesObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(4, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof CategoriesModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'name'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->name));
    }
}
