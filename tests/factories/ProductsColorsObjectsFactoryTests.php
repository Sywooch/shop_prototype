<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\ProductsColorsObjectsFactory;
use app\models\ProductsColorsModel;

/**
 * Тестирует класс app\factories\ProductsColorsObjectsFactory
 */
class ProductsColorsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id_products = 1;
    private static $_id_colors = 23;
    
    /**
     * Тестирует метод ProductsColorsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id_products'=>self::$_id_products, 'id_colors'=>self::$_id_colors],
            ],
        ]);
        
        $objectsCreator = new ProductsColorsObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof ProductsColorsModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_products'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_colors'));
        
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_products));
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_colors));
    }
}
