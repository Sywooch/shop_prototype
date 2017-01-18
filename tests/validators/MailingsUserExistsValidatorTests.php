<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\MailingsUserExistsValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture,
    MailingsFixture};
use yii\base\Model;

/**
 * Тестирует класс MailingsUserExistsValidator
 */
class MailingsUserExistsValidatorTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::class,
                'emails_mailings'=>EmailsMailingsFixture::class,
                'mailings'=>MailingsFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод MailingsUserExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        \Yii::$app->registry->clean();
        
        $model = new class() extends Model {
            public $email;
            public $id = [];
        };
        
        $reflection = new \ReflectionProperty($model, 'email');
        $reflection->setValue($model, self::$_dbClass->emails['email_1']['email']);
        
        $reflection = new \ReflectionProperty($model, 'id');
        $reflection->setValue($model, [self::$_dbClass->mailings['mailing_1']['id']]);
        
        $validator = new MailingsUserExistsValidator();
        $validator->validateAttribute($model, 'id');
        
        $this->assertCount(1, $model->errors);
        $this->assertArrayHasKey('id', $model->errors);
        $this->assertEquals($model->errors['id'][0], 'Вы уже подписаны на  эти рассылки');
        
        $model = new class() extends Model {
            public $email;
            public $id = [];
        };
        
        $reflection = new \ReflectionProperty($model, 'email');
        $reflection->setValue($model, self::$_dbClass->emails['email_1']['email']);
        
        $reflection = new \ReflectionProperty($model, 'id');
        $reflection->setValue($model, [self::$_dbClass->mailings['mailing_1']['id'], 3]);
        
        $validator = new MailingsUserExistsValidator();
        $validator->validateAttribute($model, 'id');
        
        $this->assertEmpty($model->errors);
        
        $model = new class() extends Model {
            public $email;
            public $id = [];
        };
        
        $reflection = new \ReflectionProperty($model, 'email');
        $reflection->setValue($model, self::$_dbClass->emails['email_1']['email']);
        
        $reflection = new \ReflectionProperty($model, 'id');
        $reflection->setValue($model, [15]);
        
        $validator = new MailingsUserExistsValidator();
        $validator->validateAttribute($model, 'id');
        
        $this->assertEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
