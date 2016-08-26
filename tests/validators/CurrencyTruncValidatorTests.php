<?php

namespace app\tests\validators;

use app\tests\{DbManager,
    MockModel};
use app\validators\CurrencyTruncValidator;

/**
 * Тестирует класс app\validators\CurrencyTruncValidator
 */
class CurrencyTruncValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_string = 'UaHJhJJJ';
    private static $_expectedString = 'UAH';
    
    /**
     * Тестирует метод CurrencyTruncValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new MockModel();
        $model->name = self::$_string;
        
        $validator = new CurrencyTruncValidator();
        $validator->validateAttribute($model, 'name');
        
        $this->assertEquals(self::$_expectedString, $model->name);
    }
}
