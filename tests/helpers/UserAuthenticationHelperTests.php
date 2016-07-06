<?php

namespace app\tests\helpers;

use app\helpers\UserAuthenticationHelper;
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
    private static $_login = 'Somelogin';
    private static $_login2 = 'twologin';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_rawPassword = 'gH8Ujhf';
    private static $_hashRawPassword;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        self::$_hashRawPassword = password_hash(self::$_rawPassword, PASSWORD_DEFAULT);
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[password]]=:password, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':password'=>self::$_hashRawPassword, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует метод UserAuthenticationHelper::fill
     */
    public function testFill()
    {
        $usersModel = new UsersModel();
        $usersModel->login = self::$_login;
        $usersModel->rawPassword = self::$_rawPassword;
        
        UserAuthenticationHelper::clean();
        
        UserAuthenticationHelper::fill($usersModel);
        
        $this->assertEquals(self::$_id, \Yii::$app->user->id);
        $this->assertEquals(self::$_login, \Yii::$app->user->login);
        $this->assertEquals(self::$_name, \Yii::$app->user->name);
        $this->assertEquals(self::$_surname, \Yii::$app->user->surname);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_emails);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_phones);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_address);
        
        UserAuthenticationHelper::clean();
        
        UserAuthenticationHelper::$_filedsToUser[] = 'password';
        UserAuthenticationHelper::fill($usersModel);
        
        $this->assertEquals(self::$_id, \Yii::$app->user->id);
        $this->assertEquals(self::$_login, \Yii::$app->user->login);
        $this->assertTrue(password_verify(self::$_rawPassword, \Yii::$app->user->password));
        $this->assertEquals(self::$_name, \Yii::$app->user->name);
        $this->assertEquals(self::$_surname, \Yii::$app->user->surname);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_emails);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_phones);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_address);
    }
    
    /**
     * Тестирует метод UserAuthenticationHelper::clean
     */
    public function testClean()
    {
        $usersModel = new UsersModel();
        $usersModel->login = self::$_login;
        $usersModel->rawPassword = self::$_rawPassword;
        
        UserAuthenticationHelper::clean();
        
        UserAuthenticationHelper::fill($usersModel);
        
        $this->assertEquals(self::$_id, \Yii::$app->user->id);
        $this->assertEquals(self::$_login, \Yii::$app->user->login);
        $this->assertEquals(self::$_name, \Yii::$app->user->name);
        $this->assertEquals(self::$_surname, \Yii::$app->user->surname);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_emails);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_phones);
        $this->assertEquals(self::$_id, \Yii::$app->user->id_address);
        
        UserAuthenticationHelper::clean();
        
        //$this->assertEquals(UserAuthenticationHelper::$_cleanArray['id'], \Yii::$app->user->id);
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['login'], \Yii::$app->user->login);
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['rawPassword'], \Yii::$app->user->rawPassword);
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['name'], \Yii::$app->user->name);
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['surname'], \Yii::$app->user->surname);
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['id_emails'], \Yii::$app->user->id_emails);
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['id_phones'], \Yii::$app->user->id_phones);
        $this->assertEquals(UserAuthenticationHelper::$_cleanArray['id_address'], \Yii::$app->user->id_address);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
