<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\{ColorsModel,
    ProductsModel,
    PurchasesModel,
    SizesModel};

/**
 * Тестирует класс app\models\PurchasesModel
 */
class PurchasesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>'app\tests\sources\fixtures\PurchasesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\PurchasesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\PurchasesModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DELETE_FROM_CART'));
        
        $model = new PurchasesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('id_user', $model->attributes));
        $this->assertTrue(array_key_exists('id_name', $model->attributes));
        $this->assertTrue(array_key_exists('id_surname', $model->attributes));
        $this->assertTrue(array_key_exists('id_email', $model->attributes));
        $this->assertTrue(array_key_exists('id_phone', $model->attributes));
        $this->assertTrue(array_key_exists('id_address', $model->attributes));
        $this->assertTrue(array_key_exists('id_city', $model->attributes));
        $this->assertTrue(array_key_exists('id_country', $model->attributes));
        $this->assertTrue(array_key_exists('id_postcode', $model->attributes));
        $this->assertTrue(array_key_exists('id_product', $model->attributes)); 
        $this->assertTrue(array_key_exists('quantity', $model->attributes)); 
        $this->assertTrue(array_key_exists('id_color', $model->attributes)); 
        $this->assertTrue(array_key_exists('id_size', $model->attributes));
        $this->assertTrue(array_key_exists('price', $model->attributes)); 
        $this->assertTrue(array_key_exists('id_delivery', $model->attributes)); 
        $this->assertTrue(array_key_exists('id_payment', $model->attributes)); 
        $this->assertTrue(array_key_exists('received', $model->attributes)); 
        $this->assertTrue(array_key_exists('received_date', $model->attributes));
        $this->assertTrue(array_key_exists('processed', $model->attributes)); 
        $this->assertTrue(array_key_exists('canceled', $model->attributes)); 
        $this->assertTrue(array_key_exists('shipped', $model->attributes)); 
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->purchases['purchase_1'];
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_DELETE_FROM_CART]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'], 
        ];
        
        $this->assertEquals($fixture['id_product'], $model->id_product);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $fixture = self::$_dbClass->purchases['purchase_2'];
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_DELETE_FROM_CART]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id_product', $model->errors));
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_DELETE_FROM_CART]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует метод PurchasesModel::getProduct
     */
    public function testGetProduct()
    {
        $fixture = self::$_dbClass->purchases['purchase_2'];
        
        $model = PurchasesModel::find()->where(['purchases.id'=>$fixture['id']])->one();
        
        $this->assertTrue($model->product instanceof ProductsModel);
    }
    
    /**
     * Тестирует метод PurchasesModel::getColor
     */
    public function testGetColor()
    {
        $fixture = self::$_dbClass->purchases['purchase_2'];
        
        $model = PurchasesModel::find()->where(['purchases.id'=>$fixture['id']])->one();
        
        $this->assertTrue($model->color instanceof ColorsModel);
    }
    
    /**
     * Тестирует метод PurchasesModel::getSize
     */
    public function testGetSize()
    {
        $fixture = self::$_dbClass->purchases['purchase_2'];
        
        $model = PurchasesModel::find()->where(['purchases.id'=>$fixture['id']])->one();
        
        $this->assertTrue($model->size instanceof SizesModel);
    }
    
    /**
     * Тестирует метод PurchasesModel::batchInsert
     */
    public function testBatchInsert()
    {
        \Yii::$app->db->createCommand('DELETE FROM {{purchases}}')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{purchases}}')->queryAll()));
        
        $result = PurchasesModel::batchInsert([['id_product'=>1, 'quantity'=>1, 'id_color'=>1, 'id_size'=>1], ['id_product'=>2, 'quantity'=>1, 'id_color'=>2, 'id_size'=>1]], 1, 2, 1, 1, 2, 1, 1, 2, 1, 2, 2);
        $this->assertTrue(is_int($result));
        $this->assertEquals(2, $result);
        
        $this->assertFalse(empty($result = \Yii::$app->db->createCommand('SELECT * FROM {{purchases}}')->queryAll()));
        $this->assertEquals(2, count($result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
