<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\EmailExistsCreateValidator;
use app\tests\DbManager;
use app\models\EmailsModel;

/**
 * Тестирует класс app\validators\EmailExistsCreateValidator
 */
class EmailExistsCreateValidatorTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>'app\tests\source\fixtures\EmailsFixture',
                'users'=>'app\tests\source\fixtures\UsersFixture',
            ],
        ]);
        
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод EmailExistsCreateValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $fixture = self::$_dbClass->emails['email_1'];
        
        $model = new EmailsModel();
        $model->email = $fixture['email'];
        
        $validator = new EmailExistsCreateValidator();
        $validator->validateAttribute($model, 'email');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
        $this->assertEquals(\Yii::t('base', 'This email is already registered!'), $model->errors['email'][0]);
        
        $model = new EmailsModel();
        $model->email = 'some@some.com';
        
        $validator = new EmailExistsCreateValidator();
        $validator->validateAttribute($model, 'email');
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод EmailExistsCreateValidator::validate
     */
    public function testValidate()
    {
        $fixture = self::$_dbClass->emails['email_1'];
        
        $validator = new EmailExistsCreateValidator();
        $result = $validator->validate($fixture['email']);
        
        $this->assertTrue($result);
        
        $validator = new EmailExistsCreateValidator();
        $result = $validator->validate('some@some.com');
        
        $this->assertFalse($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
