<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\source\fixtures\{EmailsFixture,
    UsersFixture};
use app\models\{EmailsModel,
    UsersModel};

/**
 * Тестирует класс app\models\EmailsModel
 */
class EmailsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::className(),
                'users'=>UsersFixture::className(),
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\EmailsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\EmailsModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
        $model = new EmailsModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('email', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->emails['email_1'];
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'email'=>$fixture['email'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['email'], $model->email);
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'email'=>$fixture['email'], 
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['email'], $model->email);
    }
    
    /**
     * Тестирует метод EmailsModel::getUsers
     */
    public function testGetUsers()
    {
        $fixture = self::$_dbClass->emails['email_1'];
        
        $model = EmailsModel::find()->where(['emails.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_object($model->users));
        $this->assertTrue($model->users instanceof UsersModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
