<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\EmailsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует EmailsModel
 */
class EmailsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_email = 'some@some.com';
    private static $_notEmail = 'some';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\EmailsModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
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
        $model = new EmailsModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, 'email'));
        $this->assertTrue(property_exists($model, '_id'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id, 'email'=>self::$_email];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->email));
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'email'=>self::$_email];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->email));
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
        $model->attributes = ['email'=>self::$_email];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
        $model->attributes = ['email'=>self::$_notEmail];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
    }
    
    /**
     * Тестирует метод EmailsModel::setId
     */
    public function testSetId()
    {
        $model = new EmailsModel();
        $model->id = self::$_id;
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует метод EmailsModel::getId
     */
    public function testGetId()
    {
        $model = new EmailsModel();
        $model->email = self::$_email;
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует возврат null в методе EmailsModel::getId
     * при условии, что необходимые для выполнения свойства пусты
     */
    public function testNullGetId()
    {
        $model = new EmailsModel();
        
       $this->assertTrue(is_null($model->id));
    }
    
    /**
     * Тестирует метод EmailsModel::getDataArray
     */
    public function testGetData()
    {
        $model = new EmailsModel();
        $model->email = self::$_email;
        
        $array = $model->getDataArray();
        
        $this->assertTrue(is_array($array));
        $this->assertTrue(array_key_exists('email', $array));
        $this->assertEquals(self::$_email, $array['email']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
