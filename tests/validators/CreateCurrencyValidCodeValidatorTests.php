<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\CreateCurrencyValidCodeValidator;

/**
 * Тестирует класс CreateCurrencyValidCodeValidator
 */
class CreateCurrencyValidCodeValidatorTests extends TestCase
{
    private $validator;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new CreateCurrencyValidCodeValidator();
    }
    
    /**
     * Тестирует метод CreateCurrencyValidCodeValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $code = 'UUU';
        };
        
        $this->validator->validateAttribute($model, 'code');
        
        $this->assertNotEmpty($model->errors);
    }
}
