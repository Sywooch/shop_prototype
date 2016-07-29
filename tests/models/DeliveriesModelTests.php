<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\DeliveriesModel;
use app\helpers\MappersHelper;

/**
 * Тестирует DeliveriesModel
 */
class DeliveriesModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_description = 'Some description';
    private static $_price = 23.12;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\DeliveriesModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{deliveries}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description, [[price]]=:price');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new DeliveriesModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, 'name'));
        $this->assertTrue(property_exists($model, 'description'));
        $this->assertTrue(property_exists($model, 'price'));
        $this->assertTrue(property_exists($model, '_id'));
        $this->assertTrue(property_exists($model, '_allDeliveries'));
        
        $this->assertTrue(method_exists($model, 'getId'));
        $this->assertTrue(method_exists($model, 'setId'));
        $this->assertTrue(method_exists($model, 'getAllDeliveries'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'description'=>self::$_description, 'price'=>self::$_price];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->description));
        $this->assertFalse(empty($model->price));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_description, $model->description);
        $this->assertEquals(self::$_price, $model->price);
        
        $model = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'description'=>self::$_description, 'price'=>self::$_price];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->description));
        $this->assertFalse(empty($model->price));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_description, $model->description);
        $this->assertEquals(self::$_price, $model->price);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод DeliveriesModel::getId
     */
    public function testGetId()
    {
        $model = new DeliveriesModel();
        $model->id = self::$_id;
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует метод DeliveriesModel::setId
     */
    public function testSetId()
    {
        $model = new DeliveriesModel();
        $model->id = self::$_id + 3;
        
        $this->assertEquals(self::$_id + 3, $model->id);
    }
    
    /**
     * Тестирует метод DeliveriesModel::getAllDeliveries
     */
    public function testGetAllDeliveries()
    {
        $model = new DeliveriesModel();
        $deliveriesArray = $model->allDeliveries;
        
        $this->assertTrue(is_array($deliveriesArray));
        $this->assertFalse(empty($deliveriesArray));
        $this->assertTrue(is_object($deliveriesArray[0]));
        $this->assertTrue($deliveriesArray[0] instanceof DeliveriesModel);
    }
    
    /**
     * Тестирует метод DeliveriesModel::getDataArray
     */
    public function testGetData()
    {
        $model = new DeliveriesModel();
        $model->id = self::$_id;
        
        $array = $model->getDataArray();
        
        $this->assertTrue(is_array($array));
        $this->assertTrue(array_key_exists('id', $array));
        $this->assertEquals(self::$_id, $array['id']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
