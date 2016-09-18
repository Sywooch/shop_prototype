<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\source\fixtures\UsersFixture;
use app\models\{EmailsModel,
    UsersModel};

/**
 * Тестирует класс app\models\UsersModel
 */
class UsersModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::className(),
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\UsersModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\UsersModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
        $model = new UsersModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('id_email', $model->attributes));
        $this->assertTrue(array_key_exists('password', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('surname', $model->attributes));
        $this->assertTrue(array_key_exists('id_phone', $model->attributes));
        $this->assertTrue(array_key_exists('id_address', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'id_email'=>$fixture['id_email'],
            'password'=>$fixture['password'],
            'name'=>$fixture['name'],
            'surname'=>$fixture['surname'],
            'id_phone'=>$fixture['id_phone'],
            'id_address'=>$fixture['id_address'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['id_email'], $model->id_email);
        $this->assertEquals($fixture['password'], $model->password);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['surname'], $model->surname);
        $this->assertEquals($fixture['id_phone'], $model->id_phone);
        $this->assertEquals($fixture['id_address'], $model->id_address);
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'id_email'=>$fixture['id_email'],
            'password'=>$fixture['password'],
            'name'=>$fixture['name'],
            'surname'=>$fixture['surname'],
            'id_phone'=>$fixture['id_phone'],
            'id_address'=>$fixture['id_address'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['id_email'], $model->id_email);
        $this->assertEquals($fixture['password'], $model->password);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['surname'], $model->surname);
        $this->assertEquals($fixture['id_phone'], $model->id_phone);
        $this->assertEquals($fixture['id_address'], $model->id_address);
    }
    
    /**
     * Тестирует метод UsersModel::getEmails
     */
    public function testGetEmails()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $model = UsersModel::find()->where(['users.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_object($model->emails));
        $this->assertTrue($model->emails instanceof EmailsModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
