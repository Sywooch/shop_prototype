<?php

namespace app\tests\validators;

use app\tests\{DbManager,
    MockModel};
use app\validators\DecimalValidator;

/**
 * Тестирует класс app\validators\DecimalValidator
 */
class DecimalValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_raw = 23;
    private static $_expected = 23.0;
    
    /**
     * Тестирует метод DecimalValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new MockModel();
        $model->size = self::$_raw;
        
        $validator = new DecimalValidator();
        $validator->validateAttribute($model, 'size');
        
        $this->assertEquals(self::$_expected, $model->size);
    }
}
