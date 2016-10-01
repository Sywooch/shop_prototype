<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\EmailExistsRegistValidator;
use app\tests\DbManager;
use app\models\EmailsModel;

/**
 * Тестирует класс app\validators\EmailExistsRegistValidator
 */
class EmailExistsRegistValidatorTests extends TestCase
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
     * Тестирует метод EmailExistsRegistValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $fixture = self::$_dbClass->emails['email_1'];
        
        $model = new EmailsModel();
        $model->email = $fixture['email'];
        
        $validator = new EmailExistsRegistValidator();
        $validator->validateAttribute($model, 'email');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
        $this->assertEquals(\Yii::t('base', 'Account with this email already exist!'), $model->errors['email'][0]);
        
        $model = new EmailsModel();
        $model->email = 'some@some.com';
        
        $validator = new EmailExistsRegistValidator();
        $validator->validateAttribute($model, 'email');
        
        $this->assertEquals(0, count($model->errors));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
