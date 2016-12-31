<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\UserEmailExistsRegValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};
use yii\base\Model;

/**
 * Тестирует класс UserEmailExistsRegValidator
 */
class UserEmailExistsRegValidatorTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::class,
                'users'=>UsersFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод UserEmailExistsRegValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        \Yii::$app->registry->clean();
        
        $fixture = self::$_dbClass->emails['email_1'];
        
        $model = new class() extends Model {
            public $email;
        };
        
        $reflection = new \ReflectionProperty($model, 'email');
        $reflection->setValue($model, $fixture['email']);
        
        $validator = new UserEmailExistsRegValidator();
        $validator->validateAttribute($model, 'email');
        
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
        
        $model = new class() extends Model {
            public $email = 'some@some.com';
        };
        
        $validator = new UserEmailExistsRegValidator();
        $validator->validateAttribute($model, 'email');
        
        $this->assertEquals(0, count($model->errors));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
