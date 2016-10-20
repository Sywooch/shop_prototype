<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\ProductDateValidator;

/**
 * Тестирует класс app\validators\ProductDateValidator
 */
class ProductDateValidatorTests extends TestCase
{
    /**
     * Тестирует метод ProductDateValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() {
            public $date;
        };
        
        $validator = new ProductDateValidator();
        $validator->validateAttribute($model, 'date');
        
        echo $model->date;
    }
}
