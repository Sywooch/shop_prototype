<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\PasswordCorrectAdminUserChangeValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\models\UsersModel;
use yii\base\Model;

/**
 * Тестирует класс PasswordCorrectAdminUserChangeValidator
 */
class PasswordCorrectAdminUserChangeValidatorTests extends TestCase
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
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод PasswordCorrectAdminUserChangeValidator::validateAttribute
     * если пароли не совпадают
     */
    public function testValidateAttributeNotEquals()
    {
        $model = new class() extends Model {
            public $id = 1;
            public $password = 'wrong';
        };
        
        $validator = new PasswordCorrectAdminUserChangeValidator();
        $validator->validateAttribute($model, 'password');
        
        $this->assertCount(1, $model->errors);
    }
    
    /**
     * Тестирует метод PasswordCorrectAdminUserChangeValidator::validateAttribute
     * если пароли совпадают
     */
    public function testValidateAttributeEquals()
    {
        $user = UsersModel::findOne(1);
        $rawPassword = $user->password;
        $rawId = $user->id;
        
        \Yii::$app->db->createCommand('UPDATE {{users}} SET [[password]]=:password WHERE [[id]]=:id')->bindValues([':password'=>password_hash($rawPassword, PASSWORD_DEFAULT), ':id'=>$rawId])->execute();
        
        $model = new class() extends Model {
            public $id;
            public $password;
        };
        $reflection = new \ReflectionProperty($model, 'id');
        $reflection->setValue($model, $rawId);
        $reflection = new \ReflectionProperty($model, 'password');
        $reflection->setValue($model, $rawPassword);
        
        $validator = new PasswordCorrectAdminUserChangeValidator();
        $validator->validateAttribute($model, 'password');
        
        $this->assertEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
