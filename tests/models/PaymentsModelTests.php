<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\PaymentsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует PaymentsModel
 */
class PaymentsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_description = 'Some description';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\PaymentsModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{payments}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description]);
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
        $model = new PaymentsModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, 'name'));
        $this->assertTrue(property_exists($model, 'description'));
        $this->assertTrue(property_exists($model, '_id'));
        $this->assertTrue(property_exists($model, '_allPayments'));
        
        $this->assertTrue(method_exists($model, 'getId'));
        $this->assertTrue(method_exists($model, 'setId'));
        $this->assertTrue(method_exists($model, 'getAllPayments'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id];
        
        $this->assertFalse(empty($model->id));
        
        $this->assertEquals(self::$_id, $model->id);
        
        $model = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'name'=>self::$_name, 'description'=>self::$_description];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->description));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_description, $model->description);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('id', $model->errors));
        
        $model = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод PaymentsModel::getId
     */
    public function testGetId()
    {
        $model = new PaymentsModel();
        $model->id = self::$_id;
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует метод PaymentsModel::setId
     */
    public function testSetId()
    {
        $model = new PaymentsModel();
        $model->id = self::$_id + 3;
        
        $this->assertEquals(self::$_id + 3, $model->id);
    }
    
    /**
     * Тестирует метод PaymentsModel::getAllPayments
     */
    public function testGetAllPayments()
    {
        $model = new PaymentsModel();
        $paymentsArray = $model->allPayments;
        
        $this->assertTrue(is_array($paymentsArray));
        $this->assertFalse(empty($paymentsArray));
        $this->assertTrue(is_object($paymentsArray[0]));
        $this->assertTrue($paymentsArray[0] instanceof PaymentsModel);
    }
    
    /**
     * Тестирует метод PaymentsModel::getDataArray
     */
    public function testGetData()
    {
        $model = new PaymentsModel();
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
