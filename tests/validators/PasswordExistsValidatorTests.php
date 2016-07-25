<?php

namespace app\tests\validators;

use app\tests\DbManager;
use app\models\UsersModel;

/**
 * Тестирует валидатор PasswordExistsValidator
 */
class PasswordExistsValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_login = 'somelogin';
    private static $_rawPassword = 'somepassword';
    private static $_notExistsRawPassword = 'Hjrt6d';
    private static $_hashRawPassword;
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    
    private static $_passwordMessage = 'Неверный пароль!';
    
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
     * Тестирует метод PasswordExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        \Yii::$app->params['userFromFormForAuthentication'] = null;
        $this->assertTrue(empty(\Yii::$app->params['userFromFormForAuthentication']));
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_LOGIN_FORM]);
        $model->attributes = ['login'=>self::$_login, 'rawPassword'=>self::$_notExistsRawPassword];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('rawPassword', $model->errors));
        $this->assertEquals(1, count($model->errors['rawPassword']));
        $this->assertEquals(self::$_passwordMessage, $model->errors['rawPassword'][0]);
    }
    
    /**
     * Тестирует метод PasswordExistsValidator::validateAttribute
     * после сохранения запроса к БД в \Yii::$app->params['userFromFormForAuthentication']
     */
    public function testValidateAttributeFromParams()
    {
        $this->assertFalse(empty(\Yii::$app->params['userFromFormForAuthentication']));
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_LOGIN_FORM]);
        $model->attributes = ['login'=>self::$_login, 'rawPassword'=>self::$_notExistsRawPassword];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('rawPassword', $model->errors));
        $this->assertEquals(1, count($model->errors['rawPassword']));
        $this->assertEquals(self::$_passwordMessage, $model->errors['rawPassword'][0]);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
