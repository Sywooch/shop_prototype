<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\ProductsColorsModel;
use app\tests\source\fixtures\ProductsColorsFixture;
use app\tests\DbManager;

/**
 * Тестирует класс app\models\ProductsColorsModel
 */
class ProductsColorsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products_colors'=>ProductsColorsFixture::className(),
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ProductsColorsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ProductsColorsModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
        $model = new ProductsColorsModel();
        
        $this->assertTrue(array_key_exists('id_product', $model->attributes));
        $this->assertTrue(array_key_exists('id_color', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->products_colors['product_color_1'];
        
        $model = new ProductsColorsModel(['scenario'=>ProductsColorsModel::GET_FROM_DB]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'], 
            'id_color'=>$fixture['id_color'], 
        ];
        
        $this->assertEquals($fixture['id_product'], $model->id_product);
        $this->assertEquals($fixture['id_color'], $model->id_color);
        
        $model = new ProductsColorsModel(['scenario'=>ProductsColorsModel::GET_FROM_FORM]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'], 
            'id_color'=>$fixture['id_color'], 
        ];
        
        $this->assertEquals($fixture['id_product'], $model->id_product);
        $this->assertEquals($fixture['id_color'], $model->id_color);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
