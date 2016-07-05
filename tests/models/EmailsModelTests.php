<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\EmailsModel;

/**
 * Тестирует EmailsModel
 */
class EmailsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_email = 'some@some.com';
    private static $_email2 = 'another@some.com';
    private static $_notEmail = 'some';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\EmailsModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
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
        
        $this->assertTrue(method_exists($model, 'getId'));
        $this->assertTrue(method_exists($model, 'setId'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id + 1, 'email'=>self::$_email2];
        
        $this->assertTrue(empty($model->id));
        $this->assertFalse(empty($model->email));
        
        $this->assertNotEquals(self::$_id + 1, $model->id);
        $this->assertEquals(self::$_email2, $model->email);
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id + 2, 'email'=>self::$_email2];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->email));
        
        $this->assertEquals(self::$_id + 2, $model->id);
        $this->assertEquals(self::$_email2, $model->email);
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
     * Тестирует метод EmailsModel::getId
     */
    public function testGetId()
    {
        $model = new EmailsModel();
        $model->email = self::$_email;
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует выброс исключения в методе EmailsModel::getId
     * @expectedException ErrorException
     */
    public function testExcGetId()
    {
        $model = new EmailsModel();
        //$model->email = self::$_email;
        
       $model->id;
    }
    
    /**
     * Тестирует метод EmailsModel::setId
     */
    public function testSetId()
    {
        $model = new EmailsModel();
        $model->id = self::$_id + 3;
        
        $this->assertEquals(self::$_id + 3, $model->id);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
