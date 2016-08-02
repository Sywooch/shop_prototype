<?php

namespace app\tests\validators;

use app\tests\DbManager;
use app\validators\EmailExistsValidator;
use app\models\{UsersModel, EmailsModel};
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\validators\EmailExistsValidator
 */
class EmailExistsValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_email = 'some@some.com';
    private static $_email2 = 'some2@some2.com';
    private static $_notAddedEmail = 'empty@some.com';
    private static $_notEmail = 'some';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    
    private static $_registartionMessage = 'Аккаунт с таким email уже существует!';
    private static $_loginMessage = 'Аккаунт с таким email не существует!';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id + 1, ':email'=>self::$_email2]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод EmailExistsValidator::validateAttribute
     * для сценария EmailsModel::GET_FROM_REGISTRATION_FORM
     * при условии, что передан email, связанный с аккаунтом
     */
    public function testValidateAttributeRegistration()
    {
        \Yii::$app->params['userFromFormForAuthentication'] = null;
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_REGISTRATION_FORM]);
        $model->email = self::$_email;
        
        $validator = new EmailExistsValidator();
        $validator->validateAttribute($model, 'email');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
        $this->assertEquals(1, count($model->errors['email']));
        $this->assertEquals(self::$_registartionMessage, $model->errors['email'][0]);
    }
    
    /**
     * Тестирует метод EmailExistsValidator::validateAttribute
     * для сценария EmailsModel::GET_FROM_REGISTRATION_FORM
     * при условии, что передан email, связанный с аккаунтом
     * после сохранения запроса к БД в \Yii::$app->params['userFromFormForAuthentication']
     */
    public function testValidateAttributeRegistrationFromParams()
    {
        $this->assertFalse(empty(\Yii::$app->params['userFromFormForAuthentication']));
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_REGISTRATION_FORM]);
        $model->email = self::$_email;
        
        $validator = new EmailExistsValidator();
        $validator->validateAttribute($model, 'email');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
        $this->assertEquals(1, count($model->errors['email']));
        $this->assertEquals(self::$_registartionMessage, $model->errors['email'][0]);
    }
    
    /**
     * Тестирует метод EmailExistsValidator::validateAttribute
     * для сценария EmailsModel::GET_FROM_REGISTRATION_FORM
     * при условии, что передан email, не связанный с аккаунтом
     */
    public function testValidateAttributeRegistrationNotRelated()
    {
        \Yii::$app->params['userFromFormForAuthentication'] = null;
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_REGISTRATION_FORM]);
        $model->email = self::$_email2;
        
        $validator = new EmailExistsValidator();
        $validator->validateAttribute($model, 'email');
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод EmailExistsValidator::validateAttribute
     * для сценария EmailsModel::GET_FROM_REGISTRATION_FORM
     * при условии, что передан несуществующий email
     */
    public function testValidateAttributeRegistrationNotAdded()
    {
        \Yii::$app->params['userFromFormForAuthentication'] = null;
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_REGISTRATION_FORM]);
        $model->email = self::$_notAddedEmail;
        
        $validator = new EmailExistsValidator();
        $validator->validateAttribute($model, 'email');
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод EmailExistsValidator::validateAttribute
     * для сценария EmailsModel::GET_FROM_LOGIN_FORM
     * при условии, что передан несуществующий email
     */
    public function testValidateAttributeLogin()
    {
        \Yii::$app->params['userFromFormForAuthentication'] = null;
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_LOGIN_FORM]);
        $model->email = self::$_notAddedEmail;
        
        $validator = new EmailExistsValidator();
        $validator->validateAttribute($model, 'email');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
        $this->assertEquals(1, count($model->errors['email']));
        $this->assertEquals(self::$_loginMessage, $model->errors['email'][0]);
    }
    
    /**
     * Тестирует метод EmailExistsValidator::validateAttribute
     * для сценария EmailsModel::GET_FROM_LOGIN_FORM
     * при условии, что передан email, не связанный с аккаунтом
     */
    public function testValidateAttributeLoginNotRelated()
    {
        \Yii::$app->params['userFromFormForAuthentication'] = null;
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_LOGIN_FORM]);
        $model->email = self::$_email2;
        
        $validator = new EmailExistsValidator();
        $validator->validateAttribute($model, 'email');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
        $this->assertEquals(1, count($model->errors['email']));
        $this->assertEquals(self::$_loginMessage, $model->errors['email'][0]);
    }
    
    /**
     * Тестирует метод EmailExistsValidator::validateAttribute
     * для сценария EmailsModel::GET_FROM_LOGIN_FORM
     * при условии, что передан email, связанный с аккаунтом
     */
    public function testValidateAttributeLoginRelated()
    {
        \Yii::$app->params['userFromFormForAuthentication'] = null;
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_LOGIN_FORM]);
        $model->email = self::$_email;
        
        $validator = new EmailExistsValidator();
        $validator->validateAttribute($model, 'email');
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод EmailExistsValidator::validateAttribute
     * для сценария EmailsModel::GET_FROM_LOGIN_FORM
     * при условии, что передан email, связанный с аккаунтом
     * после сохранения запроса к БД в \Yii::$app->params['userFromFormForAuthentication']
     */
    public function testValidateAttributeLoginRelatedFromParams()
    {
        $this->assertFalse(empty(\Yii::$app->params['userFromFormForAuthentication']));
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_LOGIN_FORM]);
        $model->email = self::$_email;
        
        $validator = new EmailExistsValidator();
        $validator->validateAttribute($model, 'email');
        
        $this->assertEquals(0, count($model->errors));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
