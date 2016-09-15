<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\source\fixtures\ProductsSizesFixture;
use app\models\ProductsSizesModel;

/**
 * Тестирует класс app\models\ProductsSizesModel
 */
class ProductsSizesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products_sizes'=>ProductsSizesFixture::className(),
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ProductsSizesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ProductsSizesModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
        $model = new ProductsSizesModel();
        
        $this->assertTrue(array_key_exists('id_product', $model->attributes));
        $this->assertTrue(array_key_exists('id_size', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->products_sizes['product_size_1'];
        
        $model = new ProductsSizesModel(['scenario'=>ProductsSizesModel::GET_FROM_DB]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'], 
            'id_size'=>$fixture['id_size'], 
        ];
        
        $this->assertEquals($fixture['id_product'], $model->id_product);
        $this->assertEquals($fixture['id_size'], $model->id_size);
        
        $model = new ProductsSizesModel(['scenario'=>ProductsSizesModel::GET_FROM_FORM]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'], 
            'id_size'=>$fixture['id_size'], 
        ];
        
        $this->assertEquals($fixture['id_product'], $model->id_product);
        $this->assertEquals($fixture['id_size'], $model->id_size);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
