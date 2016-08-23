<?php

namespace app\test\models;

use app\models\ProductsSizesModel;

/**
 * Тестирует ProductsSizesModel
 */
class ProductsSizesModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id_products = 2;
    private static $_id_sizes = 23;
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\ProductsSizesModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new ProductsSizesModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FOR_ADD_PRODUCT'));
        
        $this->assertTrue(property_exists($model, 'id_products'));
        $this->assertTrue(property_exists($model, 'id_sizes'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new ProductsSizesModel(['scenario'=>ProductsSizesModel::GET_FROM_DB]);
        $model->attributes = ['id_products'=>self::$_id_products, 'id_sizes'=>self::$_id_sizes];
        
        $this->assertFalse(empty($model->id_products));
        $this->assertFalse(empty($model->id_sizes));
        $this->assertEquals(self::$_id_products, $model->id_products);
        $this->assertEquals(self::$_id_sizes, $model->id_sizes);
        
        $model = new ProductsSizesModel(['scenario'=>ProductsSizesModel::GET_FOR_ADD_PRODUCT]);
        $model->attributes = ['id_products'=>self::$_id_products, 'id_sizes'=>self::$_id_sizes];
        
        $this->assertFalse(empty($model->id_products));
        $this->assertFalse(empty($model->id_sizes));
        $this->assertEquals(self::$_id_products, $model->id_products);
        $this->assertEquals(self::$_id_sizes, $model->id_sizes);
    }
}
