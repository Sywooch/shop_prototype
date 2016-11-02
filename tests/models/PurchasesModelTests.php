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
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_SAVE_PURCHASE'));
        
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
        ];
        
        $this->assertEquals($fixture['id_product'], $model->id_product);
        $this->assertEquals($fixture['quantity'], $model->quantity);
        $this->assertEquals($fixture['id_color'], $model->id_color);
        $this->assertEquals($fixture['id_size'], $model->id_size);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_DELETE_FROM_CART]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'], 
        ];
        
        $this->assertEquals($fixture['id_product'], $model->id_product);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_SAVE_PURCHASE]);
        $model->attributes = [
            'id_user'=>$fixture['id_user'], 
            'id_name'=>$fixture['id_name'],
            'id_surname'=>$fixture['id_surname'],
            'id_email'=>$fixture['id_email'],
            'id_phone'=>$fixture['id_phone'],
            'id_address'=>$fixture['id_address'],
            'id_city'=>$fixture['id_city'],
            'id_country'=>$fixture['id_country'],
            'id_postcode'=>$fixture['id_postcode'],
            'id_product'=>$fixture['id_product'],
            'quantity'=>$fixture['quantity'],
            'id_color'=>$fixture['id_color'],
            'id_size'=>$fixture['id_size'],
            'id_delivery'=>$fixture['id_delivery'],
            'id_payment'=>$fixture['id_payment'],
            'received'=>$fixture['received'],
            'received_date'=>$fixture['received_date'],
        ];
        
        $this->assertEquals($fixture['id_user'], $model->id_user);
        $this->assertEquals($fixture['id_name'], $model->id_name);
        $this->assertEquals($fixture['id_surname'], $model->id_surname);
        $this->assertEquals($fixture['id_email'], $model->id_email);
        $this->assertEquals($fixture['id_phone'], $model->id_phone);
        $this->assertEquals($fixture['id_address'], $model->id_address);
        $this->assertEquals($fixture['id_city'], $model->id_city);
        $this->assertEquals($fixture['id_country'], $model->id_country);
        $this->assertEquals($fixture['id_postcode'], $model->id_postcode);
        $this->assertEquals($fixture['id_product'], $model->id_product);
        $this->assertEquals($fixture['quantity'], $model->quantity);
        $this->assertEquals($fixture['id_color'], $model->id_color);
        $this->assertEquals($fixture['id_size'], $model->id_size);
        $this->assertEquals($fixture['id_delivery'], $model->id_delivery);
        $this->assertEquals($fixture['id_payment'], $model->id_payment);
        $this->assertEquals($fixture['received'], $model->received);
        $this->assertEquals($fixture['received_date'], $model->received_date);
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
        
        $this->assertEquals(4, count($model->errors));
        $this->assertTrue(array_key_exists('id_product', $model->errors));
        $this->assertTrue(array_key_exists('quantity', $model->errors));
        $this->assertTrue(array_key_exists('id_color', $model->errors));
        $this->assertTrue(array_key_exists('id_size', $model->errors));
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'], 
            'quantity'=>$fixture['quantity'],
            'id_color'=>$fixture['id_color'],
            'id_size'=>$fixture['id_size'],
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
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_SAVE_PURCHASE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(16, count($model->errors));
        $this->assertTrue(array_key_exists('id_name', $model->errors));
        $this->assertTrue(array_key_exists('id_surname', $model->errors));
        $this->assertTrue(array_key_exists('id_email', $model->errors));
        $this->assertTrue(array_key_exists('id_phone', $model->errors));
        $this->assertTrue(array_key_exists('id_address', $model->errors));
        $this->assertTrue(array_key_exists('id_city', $model->errors));
        $this->assertTrue(array_key_exists('id_country', $model->errors));
        $this->assertTrue(array_key_exists('id_postcode', $model->errors));
        $this->assertTrue(array_key_exists('id_product', $model->errors));
        $this->assertTrue(array_key_exists('quantity', $model->errors));
        $this->assertTrue(array_key_exists('id_color', $model->errors));
        $this->assertTrue(array_key_exists('id_size', $model->errors));
        $this->assertTrue(array_key_exists('id_delivery', $model->errors));
        $this->assertTrue(array_key_exists('id_payment', $model->errors));
        $this->assertTrue(array_key_exists('received', $model->errors));
        $this->assertTrue(array_key_exists('received_date', $model->errors));
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_SAVE_PURCHASE]);
        $model->attributes = [
            'id_name'=>$fixture['id_name'],
            'id_surname'=>$fixture['id_surname'],
            'id_email'=>$fixture['id_email'],
            'id_phone'=>$fixture['id_phone'],
            'id_address'=>$fixture['id_address'],
            'id_city'=>$fixture['id_city'],
            'id_country'=>$fixture['id_country'],
            'id_postcode'=>$fixture['id_postcode'],
            'id_product'=>$fixture['id_product'],
            'quantity'=>$fixture['quantity'],
            'id_color'=>$fixture['id_color'],
            'id_size'=>$fixture['id_size'],
            'id_delivery'=>$fixture['id_delivery'],
            'id_payment'=>$fixture['id_payment'],
            'received'=>$fixture['received'],
            'received_date'=>$fixture['received_date'],
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
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->purchases['purchase_1'];
        $fixture2 = self::$_dbClass->purchases['purchase_2'];
        
        $purchasesQuery = PurchasesModel::find();
        $purchasesQuery->extendSelect(['id', 'id_user']);
        $purchasesArray = $purchasesQuery->allMap('id', 'id_user');
        
        $this->assertFalse(empty($purchasesArray));
        $this->assertTrue(array_key_exists($fixture['id'], $purchasesArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $purchasesArray));
        $this->assertTrue(in_array($fixture['id_user'], $purchasesArray));
        $this->assertTrue(in_array($fixture2['id_user'], $purchasesArray));
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
