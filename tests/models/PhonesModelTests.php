<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\PhonesModel;

/**
 * Тестирует PhonesModel
 */
class PhonesModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_phone = '+396548971203';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\PhonesModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{phones}} SET [[id]]=:id, [[phone]]=:phone');
        $command->bindValues([':id'=>self::$_id, ':phone'=>self::$_phone]);
        $command->execute();
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new PhonesModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, '_id'));
        $this->assertTrue(property_exists($model, 'phone'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id, 'phone'=>self::$_phone];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->phone));
        
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'phone'=>self::$_phone];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->phone));
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('phone', $model->errors));
        
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_FORM]);
        $model->attributes = ['phone'=>self::$_phone];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_FORM]);
        $model->attributes = ['phone'=>'<script src="/my/script.js"></script>' . self::$_phone];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        $this->assertEquals(self::$_phone, $model->phone);
    }
    
    /**
     * Тестирует метод PhonesModel::getId
     */
    public function testGetId()
    {
        $model = new PhonesModel();
        $model->phone = self::$_phone;
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует возврат null в методе PhonesModel::getId
     * при условии, что необходимые для выполнения свойства пусты
     */
    public function testNullGetId()
    {
        $model = new PhonesModel();
        
       $this->assertTrue(is_null($model->id));
    }
    
    /**
     * Тестирует метод PhonesModel::setId
     */
    public function testSetId()
    {
        $model = new PhonesModel();
        $model->id = self::$_id + 3;
        
        $this->assertEquals(self::$_id + 3, $model->id);
    }
    
    /**
     * Тестирует метод PhonesModel::getDataArray
     */
    public function testGetData()
    {
        $model = new PhonesModel();
        $model->phone = self::$_phone;
        
        $array = $model->getDataArray();
        
        $this->assertTrue(is_array($array));
        $this->assertTrue(array_key_exists('phone', $array));
        $this->assertEquals(self::$_phone, $array['phone']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
