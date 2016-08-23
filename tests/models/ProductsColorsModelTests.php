<?php

namespace app\test\models;

use app\models\ProductsColorsModel;

/**
 * Тестирует ProductsColorsModel
 */
class ProductsColorsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id_products = 2;
    private static $_id_colors = 23;
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\ProductsColorsModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new ProductsColorsModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_ADD_PRODUCT'));
        
        $this->assertTrue(property_exists($model, 'id_products'));
        $this->assertTrue(property_exists($model, 'id_colors'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new ProductsColorsModel(['scenario'=>ProductsColorsModel::GET_FROM_DB]);
        $model->attributes = ['id_products'=>self::$_id_products, 'id_colors'=>self::$_id_colors];
        
        $this->assertFalse(empty($model->id_products));
        $this->assertFalse(empty($model->id_colors));
        $this->assertEquals(self::$_id_products, $model->id_products);
        $this->assertEquals(self::$_id_colors, $model->id_colors);
        
        $model = new ProductsColorsModel(['scenario'=>ProductsColorsModel::GET_FOR_ADD_PRODUCT]);
        $model->attributes = ['id_products'=>self::$_id_products, 'id_colors'=>self::$_id_colors];
        
        $this->assertFalse(empty($model->id_products));
        $this->assertFalse(empty($model->id_colors));
        $this->assertEquals(self::$_id_products, $model->id_products);
        $this->assertEquals(self::$_id_colors, $model->id_colors);
    }
}
