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
    private static $_password = 'somepassword';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_email = 'some@some.com';
    private static $_phone = '+396548971203';
    private static $_address = 'Some Address';
    private static $_city = 'Some city';
    private static $_country = 'Some country';
    private static $_postcode = 'F12345';
    private static $_rulesFromForm = [1,2];
    private static $_expected = 'Пользователь с таким логином уже существует!';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{phones}} SET [[id]]=:id, [[phone]]=:phone');
        $command->bindValues([':id'=>self::$_id, ':phone'=>self::$_phone]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{address}} SET [[id]]=:id, [[address]]=:address, [[city]]=:city, [[country]]=:country, [[postcode]]=:postcode');
        $command->bindValues([':id'=>self::$_id, ':address'=>self::$_address, ':city'=>self::$_city, ':country'=>self::$_country, ':postcode'=>self::$_postcode]);
        $command->execute();
        
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
        $this->assertEquals(self::$_expected, $model->errors['login'][0]);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
