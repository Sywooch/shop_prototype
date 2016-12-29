<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\PasswordCorrectAuthValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};
use yii\base\Model;

/**
 * Тестирует класс PasswordCorrectAuthValidator
 */
class PasswordCorrectAuthValidatorTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::class,
                'users'=>UsersFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод PasswordCorrectAuthValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $fixtureEmail = self::$dbClass->emails['email_1'];
        $fixtureUser = self::$dbClass->users['user_1'];
        
        $model = new class() extends Model {
            public $email;
            public $password = 'wrong';
        };
        
        $reflection = new \ReflectionProperty($model, 'email');
        $reflection->setValue($model, $fixtureEmail['email']);
        
        $validator = new PasswordCorrectAuthValidator();
        $validator->validateAttribute($model, 'password');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('password', $model->errors));
        
        $fixtureEmail = self::$dbClass->emails['email_2'];
        $fixtureUser = self::$dbClass->users['user_2'];
        
        $model = new class() extends Model {
            public $email;
            public $password;
        };
        
        \Yii::$app->db->createCommand('UPDATE {{users}} SET [[password]]=:password WHERE [[id]]=:id')->bindValues([':password'=>password_hash($fixtureUser['password'], PASSWORD_DEFAULT), ':id'=>$fixtureUser['id']])->execute();
        
        $reflection = new \ReflectionProperty($model, 'email');
        $reflection->setValue($model, $fixtureEmail['email']);
        
        $reflection = new \ReflectionProperty($model, 'password');
        $reflection->setValue($model, $fixtureUser['password']);
        
        $validator = new PasswordCorrectAuthValidator();
        $validator->validateAttribute($model, 'password');
        
        $this->assertEquals(0, count($model->errors));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
