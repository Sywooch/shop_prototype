<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\ProductsBrandsObjectsFactory;
use app\models\ProductsBrandsModel;

/**
 * Тестирует класс app\factories\ProductsBrandsObjectsFactory
 */
class ProductsBrandsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id_products = 1;
    private static $_id_brands = 23;
    
    /**
     * Тестирует метод ProductsBrandsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id_products'=>self::$_id_products, 'id_brands'=>self::$_id_brands],
            ],
        ]);
        
        $objectsCreator = new ProductsBrandsObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof ProductsBrandsModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_products'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_brands'));
        
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_products));
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_brands));
    }
}
