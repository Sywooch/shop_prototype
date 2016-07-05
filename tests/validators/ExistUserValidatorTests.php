<?php

namespace app\tests\validators;

use app\tests\DbManager;
use app\validators\ExistUserValidator;
use app\models\UsersModel;

/**
 * Тестирует валидатор ExistUserValidator
 */
class ExistUserValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_login = 'somelogin';
    private static $_login2 = 'notexists';
    private static $_password = 'somepassword';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_rulesFromForm = [1,2];
    private static $_registartionMessage = 'Пользователь с таким логином уже существует!';
    private static $_loginMessage = 'Пользователя с таким логином не существует!';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует метод ExistUserValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
        $model->attributes = ['login'=>self::$_login, 'password'=>self::$_password, 'rulesFromForm'=>self::$_rulesFromForm];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('login', $model->errors));
        $this->assertEquals(1, count($model->errors['login']));
        $this->assertEquals(self::$_registartionMessage, $model->errors['login'][0]);
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_LOGIN_FORM]);
        $model->attributes = ['login'=>self::$_login2, 'password'=>self::$_password];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('login', $model->errors));
        $this->assertEquals(1, count($model->errors['login']));
        $this->assertEquals(self::$_loginMessage, $model->errors['login'][0]);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
