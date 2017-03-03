<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\CreateCurrencyExistsCodeValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;

/**
 * Тестирует класс CreateCurrencyExistsCodeValidator
 */
class CreateCurrencyExistsCodeValidatorTests extends TestCase
{
    private static $_dbClass;
    private $validator;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new CreateCurrencyExistsCodeValidator();
    }
    
    /**
     * Тестирует метод CreateCurrencyExistsCodeValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $code;
        };
        $reflection = new \ReflectionProperty($model, 'code');
        $reflection->setValue($model, self::$_dbClass->currency['currency_1']['code']);
        
        $this->validator->validateAttribute($model, 'code');
        
        $this->assertNotEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
