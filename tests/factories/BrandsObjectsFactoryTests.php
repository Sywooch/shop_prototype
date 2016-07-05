<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\BrandsObjectsFactory;
use app\models\BrandsModel;

/**
 * Тестирует класс app\factories\BrandsObjectsFactory
 */
class BrandsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    private static $_brand = 'Some';
    
    /**
     * Тестирует метод BrandsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id'=>self::$_id, 'brand'=>self::$_brand],
            ],
        ]);
        
        $objectsCreator = new BrandsObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof BrandsModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'brand'));
        
        $this->assertTrue(isset($mockObject->objectsArray[0]->id));
        $this->assertTrue(isset($mockObject->objectsArray[0]->brand));
    }
}
