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
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_TO_CART'));
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
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'], 
            'quantity'=>$fixture['quantity'],
            'id_color'=>$fixture['id_color'],
            'id_size'=>$fixture['id_size'],
            'price'=>$fixture['price'],
        ];
        
        $this->assertEquals($fixture['id_product'], $model->id_product);
        $this->assertEquals($fixture['quantity'], $model->quantity);
        $this->assertEquals($fixture['id_color'], $model->id_color);
        $this->assertEquals($fixture['id_size'], $model->id_size);
        $this->assertEquals($fixture['price'], $model->price);
        
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
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(5, count($model->errors));
        $this->assertTrue(array_key_exists('id_product', $model->errors));
        $this->assertTrue(array_key_exists('quantity', $model->errors));
        $this->assertTrue(array_key_exists('id_color', $model->errors));
        $this->assertTrue(array_key_exists('id_size', $model->errors));
        $this->assertTrue(array_key_exists('price', $model->errors));
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'], 
            'quantity'=>$fixture['quantity'],
            'id_color'=>$fixture['id_color'],
            'id_size'=>$fixture['id_size'],
            'price'=>$fixture['price'],
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
        
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
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $purchasesQuery = PurchasesModel::find();
        $purchasesQuery->extendSelect(['id', 'id_user', 'id_name', 'id_surname', 'id_email', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode', 'id_product', 'quantity', 'id_color', 'id_size', 'id_delivery', 'id_payment', 'received', 'received_date', 'processed', 'canceled', 'shipped']);
        
        $queryRaw = clone $purchasesQuery;
        
        $expectedQuery = "SELECT `purchases`.`id`, `purchases`.`id_user`, `purchases`.`id_name`, `purchases`.`id_surname`, `purchases`.`id_email`, `purchases`.`id_phone`, `purchases`.`id_address`, `purchases`.`id_city`, `purchases`.`id_country`, `purchases`.`id_postcode`, `purchases`.`id_product`, `purchases`.`quantity`, `purchases`.`id_color`, `purchases`.`id_size`, `purchases`.`id_delivery`, `purchases`.`id_payment`, `purchases`.`received`, `purchases`.`received_date`, `purchases`.`processed`, `purchases`.`canceled`, `purchases`.`shipped` FROM `purchases`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $purchasesQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof PurchasesModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->purchases['purchase_1'];
        
        $purchasesQuery = PurchasesModel::find();
        $purchasesQuery->extendSelect(['id', 'id_user', 'id_name', 'id_surname', 'id_email', 'id_phone', 'id_address', 'id_city', 'id_country', 'id_postcode', 'id_product', 'quantity', 'id_color', 'id_size', 'id_delivery', 'id_payment', 'received', 'received_date', 'processed', 'canceled', 'shipped']);
        $purchasesQuery->where(['[[purchases.id]]'=>(int) $fixture['id']]);
        
        $queryRaw = clone $purchasesQuery;
        
        $expectedQuery = sprintf("SELECT `purchases`.`id`, `purchases`.`id_user`, `purchases`.`id_name`, `purchases`.`id_surname`, `purchases`.`id_email`, `purchases`.`id_phone`, `purchases`.`id_address`, `purchases`.`id_city`, `purchases`.`id_country`, `purchases`.`id_postcode`, `purchases`.`id_product`, `purchases`.`quantity`, `purchases`.`id_color`, `purchases`.`id_size`, `purchases`.`id_delivery`, `purchases`.`id_payment`, `purchases`.`received`, `purchases`.`received_date`, `purchases`.`processed`, `purchases`.`canceled`, `purchases`.`shipped` FROM `purchases` WHERE `purchases`.`id`=%d", $fixture['id']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $purchasesQuery->one();
        
        $this->assertTrue($result instanceof PurchasesModel);
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
