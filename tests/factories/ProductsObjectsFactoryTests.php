<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\ProductsObjectsFactory;
use app\models\ProductsModel;

/**
 * Тестирует класс app\factories\ProductsObjectsFactory
 */
class ProductsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод ProductsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>1, 'date'=>'Something 1', 'code'=>'Something 1', 'name'=>'Something 1', 'description'=>'Something 1', 'price'=>23, 'images'=>'Something 1'],
                ['id'=>2, 'date'=>'Something 2', 'code'=>'Something 2', 'name'=>'Something 2', 'description'=>'Something 2', 'price'=>23, 'images'=>'Something 2'],
                ['id'=>3, 'date'=>'Something 3', 'code'=>'Something 3', 'name'=>'Something 3', 'description'=>'Something 3', 'price'=>23, 'images'=>'Something 3'],
            ],
        ]);
        
        $objectsCreator = new ProductsObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(3, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof ProductsModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'date'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'code'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'name'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'description'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'price'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'images'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->date));
        $this->assertTrue(isset($mockObject->objectsArray[0]->code));
        $this->assertTrue(isset($mockObject->objectsArray[0]->name));
        $this->assertTrue(isset($mockObject->objectsArray[0]->description));
        $this->assertTrue(isset($mockObject->objectsArray[0]->price));
        $this->assertTrue(isset($mockObject->objectsArray[0]->images));
    }
}
