<?php

namespace app\tests\factories;

use app\tests\MockObject;
use app\factories\ProductsSizesObjectsFactory;
use app\models\ProductsSizesModel;

/**
 * Тестирует класс app\factories\ProductsSizesObjectsFactory
 */
class ProductsSizesObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $_id_products = 1;
    private static $_id_sizes = 23;
    
    /**
     * Тестирует метод ProductsSizesObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $mockObject = new MockObject([
            'DbArray'=>[
                ['id_products'=>self::$_id_products, 'id_sizes'=>self::$_id_sizes],
            ],
        ]);
        
        $objectsCreator = new ProductsSizesObjectsFactory();
        $objectsCreator->update($mockObject);
        
        $this->assertFalse(empty($mockObject->objectsArray));
        $this->assertEquals(1, count($mockObject->objectsArray));
        $this->assertTrue(is_object($mockObject->objectsArray[0]));
        $this->assertTrue($mockObject->objectsArray[0] instanceof ProductsSizesModel);
        
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_products'));
        $this->assertTrue(property_exists($mockObject->objectsArray[0], 'id_sizes'));
        
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_products));
        $this->assertFalse(empty($mockObject->objectsArray[0]->id_sizes));
    }
}
