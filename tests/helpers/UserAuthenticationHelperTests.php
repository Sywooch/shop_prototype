<?php

namespace app\tests\helpers;

use app\helpers\{UserAuthenticationHelper, MappersHelper};
use app\tests\DbManager;
use app\tests\MockModel;
use app\models\UsersModel;

/**
 * Тестирует app\helpers\UserAuthenticationHelper
 */
class UserAuthenticationHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_id_emails = 61;
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_rawPassword = 'gH8Ujhf';
    private static $_hashRawPassword;
    private static $_email = 'some@some.com';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        self::$_hashRawPassword = password_hash(self::$_rawPassword, PASSWORD_DEFAULT);
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id_emails, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[password]]=:password, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':password'=>self::$_hashRawPassword, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id_emails, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует значения UserAuthenticationHelper::$_cleanArray
     */
    public function testCleanArray()
    {
        $this->assertTrue(is_null(UserAuthenticationHelper::$_cleanArray['id']));
        $this->assertTrue(is_null(UserAuthenticationHelper::$_cleanArray['password']));
        $this->assertTrue(empty(UserAuthenticationHelper::$_cleanArray['rawPassword']));
        $this->assertTrue(empty(UserAuthenticationHelper::$_cleanArray['name']));
        $this->assertTrue(empty(UserAuthenticationHelper::$_cleanArray['surname']));
        $this->assertEquals(0, UserAuthenticationHelper::$_cleanArray['id_emails']);
        $this->assertEquals(0, UserAuthenticationHelper::$_cleanArray['id_phones']);
        $this->assertEquals(0, UserAuthenticationHelper::$_cleanArray['id_address']);
    }
    
    /**
     * Тестирует значения UserAuthenticationHelper::$_cleanArray
     * при условии вызова метода UserAuthenticationHelper::init()
     */
    public function testCleanArrayWithInit()
    {
        $this->assertTrue(is_null(UserAuthenticationHelper::$_cleanArray['id']));
        $this->assertTrue(is_null(UserAuthenticationHelper::$_cleanArray['password']));
        $this->assertTrue(empty(UserAuthenticationHelper::$_cleanArray['rawPassword']));
        $this->assertTrue(empty(UserAuthenticationHelper::$_cleanArray['name']));
        $this->assertTrue(empty(UserAuthenticationHelper::$_cleanArray['surname']));
        $this->assertEquals(0, UserAuthenticationHelper::$_cleanArray['id_emails']);
        $this->assertEquals(0, UserAuthenticationHelper::$_cleanArray['id_phones']);
        $this->assertEquals(0, UserAuthenticationHelper::$_cleanArray['id_address']);
    }
    
    /**
     * Тестирует метод UserAuthenticationHelper::fillFromForm
     */
    public function testFillFromForm()
    {
        $usersModel = new UsersModel();
        $usersModel->id_emails = self::$_id_emails;
        $usersModel->rawPassword = self::$_rawPassword;
        
        UserAuthenticationHelper::clean();
        
        \Yii::$app->params['userFromFormForAuthentication'] = null;
        
        UserAuthenticationHelper::fillFromForm($usersModel);
        
        $this->assertEquals(self::$_id, \Yii::$app->user->id);
        $this->assertEquals(self::$_name, \Yii::$app->user->name);
        $this->assertEquals(self::$_surname, \Yii::$app->user->surname);
        $this->assertEquals(self::$_id_emails, \Yii::$app->user->id_emails);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_phones);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_address);
    }
    
    /**
     * Тестирует метод UserAuthenticationHelper::fillFromForm
     * после сохранения запроса к БД в \Yii::$app->params['userFromFormForAuthentication']
     */
    public function testFillFromFormFromParams()
    {
        $this->assertFalse(empty(\Yii::$app->params['userFromFormForAuthentication']));
        
        $usersModel = new UsersModel();
        $usersModel->id_emails = self::$_id_emails;
        $usersModel->rawPassword = self::$_rawPassword;
        
        UserAuthenticationHelper::clean();
        
        UserAuthenticationHelper::fillFromForm($usersModel);
        
        $this->assertEquals(self::$_id, \Yii::$app->user->id);
        $this->assertEquals(self::$_name, \Yii::$app->user->name);
        $this->assertEquals(self::$_surname, \Yii::$app->user->surname);
        $this->assertEquals(self::$_id_emails, \Yii::$app->user->id_emails);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_phones);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_address);
    }
    
    /**
     * Тестирует метод UserAuthenticationHelper::clean
     */
    public function testClean()
    {
        \Yii::$app->params['userFromFormForAuthentication'] = null;
        
        $usersModel = new UsersModel();
        $usersModel->id_emails = self::$_id_emails;
        $usersModel->rawPassword = self::$_rawPassword;
        
        UserAuthenticationHelper::clean();
        
        UserAuthenticationHelper::fillFromForm($usersModel);
        
        $this->assertEquals(self::$_id, \Yii::$app->user->id);
        $this->assertEquals(self::$_name, \Yii::$app->user->name);
        $this->assertEquals(self::$_surname, \Yii::$app->user->surname);
        $this->assertEquals(self::$_id_emails, \Yii::$app->user->id_emails);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_phones);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_address);
        
        UserAuthenticationHelper::clean();
        
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['id'], \Yii::$app->user->id);
        $this->assertTrue(is_null(\Yii::$app->user->id));
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['rawPassword'], \Yii::$app->user->rawPassword);
        $this->assertTrue(is_string(\Yii::$app->user->rawPassword));
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['name'], \Yii::$app->user->name);
        $this->assertTrue(is_string(\Yii::$app->user->name));
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['surname'], \Yii::$app->user->surname);
        $this->assertTrue(is_string(\Yii::$app->user->surname));
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['id_emails'], \Yii::$app->user->id_emails);
        $this->assertTrue(is_int(\Yii::$app->user->id_emails));
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['id_phones'], \Yii::$app->user->id_phones);
        $this->assertTrue(is_int(\Yii::$app->user->id_phones));
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['id_address'], \Yii::$app->user->id_address);
        $this->assertTrue(is_int(\Yii::$app->user->id_address));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
