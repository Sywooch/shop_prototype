<?php

namespace app\test\models;

use app\models\UsersPurchasesModel;

/**
 * Тестирует UsersPurchasesModel
 */
class UsersPurchasesModelTests extends \PHPUnit_Framework_TestCase
{
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
    
    public static function setUpBeforeClass()
    {
        self::$_reflectionClass = new \ReflectionClass('app\models\UsersPurchasesModel');
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new UsersPurchasesModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'id_users'));
        $this->assertTrue(property_exists($model, 'id_products'));
        $this->assertTrue(property_exists($model, 'id_deliveries'));
        $this->assertTrue(property_exists($model, 'id_payments'));
        $this->assertTrue(property_exists($model, '_received'));
        $this->assertTrue(property_exists($model, '_received_date'));
        $this->assertTrue(property_exists($model, '_processed'));
        $this->assertTrue(property_exists($model, '_canceled'));
        $this->assertTrue(property_exists($model, '_shipped'));
        
        $this->assertTrue(method_exists($model, 'setReceived'));
        $this->assertTrue(method_exists($model, 'getReceived'));
        $this->assertTrue(method_exists($model, 'setReceived_date'));
        $this->assertTrue(method_exists($model, 'getReceived_date'));
        $this->assertTrue(method_exists($model, 'setProcessed'));
        $this->assertTrue(method_exists($model, 'getProcessed'));
        $this->assertTrue(method_exists($model, 'setCanceled'));
        $this->assertTrue(method_exists($model, 'getCanceled'));
        $this->assertTrue(method_exists($model, 'setShipped'));
        $this->assertTrue(method_exists($model, 'getShipped'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new UsersPurchasesModel(['scenario'=>UsersPurchasesModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id, 'id_users'=>self::$_id_users, 'id_products'=>self::$_id_products, 'id_deliveries'=>self::$_id_deliveries, 'id_payments'=>self::$_id_payments];
        
        $this->assertTrue(empty($model->id));
        $this->assertFalse(empty($model->id_users));
        $this->assertFalse(empty($model->id_products));
        $this->assertFalse(empty($model->id_deliveries));
        $this->assertFalse(empty($model->id_payments));
        
        $this->assertEquals(self::$_id_users, $model->id_users);
        $this->assertEquals(self::$_id_products, $model->id_products);
        $this->assertEquals(self::$_id_deliveries, $model->id_deliveries);
        $this->assertEquals(self::$_id_payments, $model->id_payments);
        
        $model = new UsersPurchasesModel(['scenario'=>UsersPurchasesModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'id_users'=>self::$_id_users, 'id_products'=>self::$_id_products, 'id_deliveries'=>self::$_id_deliveries, 'id_payments'=>self::$_id_payments, 'received'=>self::$_received, 'received_date'=>self::$_received_date, 'processed'=>self::$_processed, 'canceled'=>self::$_canceled, 'shipped'=>self::$_shipped];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->id_users));
        $this->assertFalse(empty($model->id_products));
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
        $this->assertEquals(self::$_id_deliveries, $model->id_deliveries);
        $this->assertEquals(self::$_id_payments, $model->id_payments);
        $this->assertEquals(self::$_received, $model->received);
        $this->assertEquals(self::$_received_date, $model->received_date);
        $this->assertEquals(self::$_processed, $model->processed);
        $this->assertEquals(self::$_canceled, $model->canceled);
        $this->assertEquals(self::$_shipped, $model->shipped);
    }
    
    /**
     * Тестирует метод UsersPurchasesModel::setReceived
     */
    public function testSetReceived()
    {
        $model = new UsersPurchasesModel();
        $model->received = self::$_received;
        
        $this->assertEquals(self::$_received, $model->received);
    }
    
    /**
     * Тестирует метод UsersPurchasesModel::getReceived
     */
    public function testGetReceived()
    {
        $model = new UsersPurchasesModel();
        
        $this->assertEquals(0, $model->received);
    }
    
    /**
     * Тестирует метод UsersPurchasesModel::getReceived
     * в сценарии GET_FROM_FORM
     */
    public function testGetReceivedTwo()
    {
        $model = new UsersPurchasesModel(['scenario'=>UsersPurchasesModel::GET_FROM_FORM]);
        
        $this->assertEquals(self::$_received, $model->received);
    }
    
    /**
     * Тестирует метод UsersPurchasesModel::setReceived_date
     */
    public function testSetReceived_date()
    {
        $model = new UsersPurchasesModel();
        $model->received_date = self::$_received_date;
        
        $this->assertEquals(self::$_received_date, $model->received_date);
    }
    
    /**
     * Тестирует метод UsersPurchasesModel::getReceived_date
     */
    public function testGetReceived_date()
    {
        $model = new UsersPurchasesModel();
        
        $this->assertEquals(NULL, $model->received_date);
    }
    
    /**
     * Тестирует метод UsersPurchasesModel::getReceived_date
     * в сценарии GET_FROM_FORM
     */
    public function testGetReceived_dateTwo()
    {
        $model = new UsersPurchasesModel(['scenario'=>UsersPurchasesModel::GET_FROM_FORM]);
        
        $this->assertNotEquals(NULL, $model->received_date);
    }
    
    /**
     * Тестирует метод UsersPurchasesModel::setProcessed
     */
    public function testSetProcessed()
    {
        $model = new UsersPurchasesModel();
        $model->processed = self::$_processed;
        
        $this->assertEquals(self::$_processed, $model->processed);
    }
    
    /**
     * Тестирует метод UsersPurchasesModel::getProcessed
     */
    public function testGetProcessed()
    {
        $model = new UsersPurchasesModel();
        
        $this->assertEquals(0, $model->processed);
    }
    
    /**
     * Тестирует метод UsersPurchasesModel::setCanceled
     */
    public function testSetCanceled()
    {
        $model = new UsersPurchasesModel();
        $model->canceled = self::$_canceled;
        
        $this->assertEquals(self::$_canceled, $model->canceled);
    }
    
    /**
     * Тестирует метод UsersPurchasesModel::getCanceled
     */
    public function testGetCanceled()
    {
        $model = new UsersPurchasesModel();
        
        $this->assertEquals(0, $model->canceled);
    }
    
    /**
     * Тестирует метод UsersPurchasesModel::setShipped
     */
    public function testSetShipped()
    {
        $model = new UsersPurchasesModel();
        $model->shipped = self::$_shipped;
        
        $this->assertEquals(self::$_shipped, $model->shipped);
    }
    
    /**
     * Тестирует метод UsersPurchasesModel::getShipped
     */
    public function testGetShipped()
    {
        $model = new UsersPurchasesModel();
        
        $this->assertEquals(0, $model->shipped);
    }
}
