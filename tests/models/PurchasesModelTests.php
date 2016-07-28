<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\{PurchasesModel, 
    ProductsModel, 
    ColorsModel,
    SizesModel};

/**
 * Тестирует PurchasesModel
 */
class PurchasesModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_id_users = 2;
    private static $_id_products = 3;
    private static $_id_deliveries = 4;
    private static $_id_payments = 5;
    private static $_received = 1;
    private static $_received_date = 1462453595;
    private static $_processed = 1;
    private static $_canceled = 1;
    private static $_shipped = 1;
    private static $_id_colors = 4;
    private static $_id_sizes = 5;
    private static $_quantity = 2;
    private static $_date = 1462453595;
    private static $_code = 'YU-6709';
    private static $_name = 'name';
    private static $_description = 'description';
    private static $_price = 14.45;
    private static $_images = 'images';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_color = 'gray';
    private static $_size = '46';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        self::$_reflectionClass = new \ReflectionClass('app\models\PurchasesModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[short_description]]=:short_description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id_products, ':date'=>self::$_date, ':code'=>self::$_code, ':name'=>self::$_name, ':description'=>self::$_description, ':short_description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id_colors, ':color'=>self::$_color]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id_sizes, ':size'=>self::$_size]);
        $command->execute();
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new PurchasesModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'id_users'));
        $this->assertTrue(property_exists($model, 'id_products'));
        $this->assertTrue(property_exists($model, 'quantity'));
        $this->assertTrue(property_exists($model, 'id_colors'));
        $this->assertTrue(property_exists($model, 'id_sizes'));
        $this->assertTrue(property_exists($model, 'id_deliveries'));
        $this->assertTrue(property_exists($model, 'id_payments'));
        $this->assertTrue(property_exists($model, '_received'));
        $this->assertTrue(property_exists($model, '_received_date'));
        $this->assertTrue(property_exists($model, '_processed'));
        $this->assertTrue(property_exists($model, '_canceled'));
        $this->assertTrue(property_exists($model, '_shipped'));
        $this->assertTrue(property_exists($model, '_productsObject'));
        $this->assertTrue(property_exists($model, '_colorsObject'));
        $this->assertTrue(property_exists($model, '_sizesObject'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id, 'id_users'=>self::$_id_users, 'id_products'=>self::$_id_products, 'id_colors'=>self::$_id_colors, 'id_sizes'=>self::$_id_sizes, 'quantity'=>self::$_quantity, 'id_deliveries'=>self::$_id_deliveries, 'id_payments'=>self::$_id_payments];
        
        $this->assertTrue(empty($model->id));
        $this->assertFalse(empty($model->id_users));
        $this->assertFalse(empty($model->id_products));
        $this->assertFalse(empty($model->quantity));
        $this->assertFalse(empty($model->id_colors));
        $this->assertFalse(empty($model->id_sizes));
        $this->assertFalse(empty($model->id_deliveries));
        $this->assertFalse(empty($model->id_payments));
        
        $this->assertEquals(self::$_id_users, $model->id_users);
        $this->assertEquals(self::$_id_products, $model->id_products);
        $this->assertEquals(self::$_quantity, $model->quantity);
        $this->assertEquals(self::$_id_colors, $model->id_colors);
        $this->assertEquals(self::$_id_sizes, $model->id_sizes);
        $this->assertEquals(self::$_id_deliveries, $model->id_deliveries);
        $this->assertEquals(self::$_id_payments, $model->id_payments);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'id_users'=>self::$_id_users, 'id_products'=>self::$_id_products, 'id_colors'=>self::$_id_colors, 'id_sizes'=>self::$_id_sizes, 'quantity'=>self::$_quantity, 'id_deliveries'=>self::$_id_deliveries, 'id_payments'=>self::$_id_payments, 'received'=>self::$_received, 'received_date'=>self::$_received_date, 'processed'=>self::$_processed, 'canceled'=>self::$_canceled, 'shipped'=>self::$_shipped];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->id_users));
        $this->assertFalse(empty($model->id_products));
        $this->assertFalse(empty($model->quantity));
        $this->assertFalse(empty($model->id_colors));
        $this->assertFalse(empty($model->id_sizes));
        $this->assertFalse(empty($model->id_deliveries));
        $this->assertFalse(empty($model->id_payments));
        $this->assertFalse(empty($model->received));
        $this->assertFalse(empty($model->received_date));
        $this->assertFalse(empty($model->processed));
        $this->assertFalse(empty($model->canceled));
        $this->assertFalse(empty($model->shipped));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_id_users, $model->id_users);
        $this->assertEquals(self::$_id_products, $model->id_products);
        $this->assertEquals(self::$_quantity, $model->quantity);
        $this->assertEquals(self::$_id_colors, $model->id_colors);
        $this->assertEquals(self::$_id_sizes, $model->id_sizes);
        $this->assertEquals(self::$_id_deliveries, $model->id_deliveries);
        $this->assertEquals(self::$_id_payments, $model->id_payments);
        $this->assertEquals(self::$_received, $model->received);
        $this->assertEquals(self::$_received_date, $model->received_date);
        $this->assertEquals(self::$_processed, $model->processed);
        $this->assertEquals(self::$_canceled, $model->canceled);
        $this->assertEquals(self::$_shipped, $model->shipped);
    }
    
    /**
     * Тестирует метод PurchasesModel::getReceived
     */
    public function testGetReceived()
    {
        $model = new PurchasesModel();
        
        $this->assertEquals(0, $model->received);
    }
    
    /**
     * Тестирует метод PurchasesModel::setReceived
     */
    public function testSetReceived()
    {
        $model = new PurchasesModel();
        $model->received = self::$_received;
        
        $this->assertEquals(self::$_received, $model->received);
    }
    
    /**
     * Тестирует метод PurchasesModel::setReceived_date
     */
    public function testSetReceived_date()
    {
        $model = new PurchasesModel();
        $model->received_date = self::$_received_date;
        
        $this->assertEquals(self::$_received_date, $model->received_date);
    }
    
    /**
     * Тестирует метод PurchasesModel::getReceived_date
     */
    public function testGetReceived_dateTwo()
    {
        $model = new PurchasesModel();
        
        $this->assertNotEquals(null, $model->received_date);
    }
    
    /**
     * Тестирует метод PurchasesModel::setProcessed
     */
    public function testSetProcessed()
    {
        $model = new PurchasesModel();
        $model->processed = self::$_processed;
        
        $this->assertEquals(self::$_processed, $model->processed);
    }
    
    /**
     * Тестирует метод PurchasesModel::getProcessed
     */
    public function testGetProcessed()
    {
        $model = new PurchasesModel();
        
        $this->assertEquals(0, $model->processed);
    }
    
    /**
     * Тестирует метод PurchasesModel::setCanceled
     */
    public function testSetCanceled()
    {
        $model = new PurchasesModel();
        $model->canceled = self::$_canceled;
        
        $this->assertEquals(self::$_canceled, $model->canceled);
    }
    
    /**
     * Тестирует метод PurchasesModel::getCanceled
     */
    public function testGetCanceled()
    {
        $model = new PurchasesModel();
        
        $this->assertEquals(0, $model->canceled);
    }
    
    /**
     * Тестирует метод PurchasesModel::setShipped
     */
    public function testSetShipped()
    {
        $model = new PurchasesModel();
        $model->shipped = self::$_shipped;
        
        $this->assertEquals(self::$_shipped, $model->shipped);
    }
    
    /**
     * Тестирует метод PurchasesModel::getShipped
     */
    public function testGetShipped()
    {
        $model = new PurchasesModel();
        
        $this->assertEquals(0, $model->shipped);
    }
    
    /**
     * Тестирует метод PurchasesModel::getProductsObject
     */
    public function testGetProductsObject()
    {
        $model = new PurchasesModel();
        $model->id_products = self::$_id_products;
        
        $result = $model->productsObject;
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof ProductsModel);
    }
    
    /**
     * Тестирует метод PurchasesModel::getColorsObject
     */
    public function testGetColorsObject()
    {
        $model = new PurchasesModel();
        $model->id_colors = self::$_id_colors;
        
        $result = $model->colorsObject;
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof ColorsModel);
    }
    
    /**
     * Тестирует метод PurchasesModel::getSizesObject
     */
    public function testGetSizesObject()
    {
        $model = new PurchasesModel();
        $model->id_sizes = self::$_id_sizes;
        
        $result = $model->sizesObject;
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof SizesModel);
    }
    
    /**
     * Тестирует метод PurchasesModel::getDeliveryStatus
     */
    public function testGetDeliveryStatus()
    {
        $model = new PurchasesModel();
        $model->processed = true;
        $result = $model->getDeliveryStatus();
        
        $this->assertTrue(is_string($result));
        $this->assertEquals(\Yii::$app->params['deliveryStatusesArray']['processed'], $result);
        
        $model = new PurchasesModel();
        $model->canceled = true;
        $result = $model->getDeliveryStatus();
        
        $this->assertTrue(is_string($result));
        $this->assertEquals(\Yii::$app->params['deliveryStatusesArray']['canceled'], $result);
        
        $model = new PurchasesModel();
        $model->shipped = true;
        $result = $model->getDeliveryStatus();
        
        $this->assertTrue(is_string($result));
        $this->assertEquals(\Yii::$app->params['deliveryStatusesArray']['shipped'], $result);
        
        $model = new PurchasesModel();
        $result = $model->getDeliveryStatus();
        
        $this->assertTrue(is_string($result));
        $this->assertEquals(\Yii::$app->params['deliveryStatusesArray']['received'], $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
