<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\PasswordCorrectChangeValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\models\UsersModel;
use yii\base\Model;

/**
 * Тестирует класс PasswordCorrectChangeValidator
 */
class PasswordCorrectChangeValidatorTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод PasswordCorrectChangeValidator::validateAttribute
     * если пароли не совпадают
     */
    public function testValidateAttributeNotEquals()
    {
        \Yii::$app->registry->clean();
        
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $model = new class() extends Model {
            public $password = 'wrong';
        };
        
        $validator = new PasswordCorrectChangeValidator();
        $validator->validateAttribute($model, 'password');
        
        $this->assertCount(1, $model->errors);
        $this->assertArrayHasKey('password', $model->errors);
    }
    
    /**
     * Тестирует метод PasswordCorrectChangeValidator::validateAttribute
     * если пароли совпадают
     */
    public function testValidateAttributeEquals()
    {
        \Yii::$app->registry->clean();
        \Yii::$app->user->logout();
        
        $user = UsersModel::findOne(1);
        $rawPassword = $user->password;
        
        \Yii::$app->db->createCommand('UPDATE {{users}} SET [[password]]=:password WHERE [[id]]=:id')->bindValues([':password'=>password_hash($user->password, PASSWORD_DEFAULT), ':id'=>$user->id])->execute();
        
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $model = new class() extends Model {
            public $password;
        };
        
        $reflection = new \ReflectionProperty($model, 'password');
        $reflection->setValue($model, $rawPassword);
        
        $validator = new PasswordCorrectChangeValidator();
        $validator->validateAttribute($model, 'password');
        
        $this->assertEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
