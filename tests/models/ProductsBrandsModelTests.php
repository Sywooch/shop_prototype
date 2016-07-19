<?php

namespace app\test\models;

use app\models\ProductsBrandsModel;

/**
 * Тестирует ProductsBrandsModel
 */
class ProductsBrandsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id_products = 2;
    private static $_id_brands = 23;
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\ProductsBrandsModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new ProductsBrandsModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_PRODUCT_FORM'));
        
        $this->assertTrue(property_exists($model, 'id_products'));
        $this->assertTrue(property_exists($model, 'id_brands'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new ProductsBrandsModel(['scenario'=>ProductsBrandsModel::GET_FROM_DB]);
        $model->attributes = ['id_products'=>self::$_id_products, 'id_brands'=>self::$_id_brands];
        
        $this->assertFalse(empty($model->id_products));
        $this->assertFalse(empty($model->id_brands));
        $this->assertEquals(self::$_id_products, $model->id_products);
        $this->assertEquals(self::$_id_brands, $model->id_brands);
        
        $model = new ProductsBrandsModel(['scenario'=>ProductsBrandsModel::GET_FROM_ADD_PRODUCT_FORM]);
        $model->attributes = ['id_products'=>self::$_id_products, 'id_brands'=>self::$_id_brands];
        
        $this->assertFalse(empty($model->id_products));
        $this->assertFalse(empty($model->id_brands));
        $this->assertEquals(self::$_id_products, $model->id_products);
        $this->assertEquals(self::$_id_brands, $model->id_brands);
    }
}
