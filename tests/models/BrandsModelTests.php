<?php

namespace app\test\models;

use app\models\BrandsModel;

/**
 * Тестирует BrandsModel
 */
class BrandsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_brand = 'Some Brand';
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\BrandsModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new BrandsModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_PRODUCT_FORM'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'brand'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'brand'=>self::$_brand];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->brand));
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_brand, $model->brand);
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_ADD_PRODUCT_FORM]);
        $model->attributes = ['id'=>self::$_id];
        
        $this->assertFalse(empty($model->id));
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_ADD_PRODUCT_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_ADD_PRODUCT_FORM]);
        $model->attributes = ['id'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
}
