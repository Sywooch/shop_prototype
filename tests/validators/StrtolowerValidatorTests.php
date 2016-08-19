<?php

namespace app\tests\validators;

use app\tests\{DbManager,
    MockModel};
use app\validators\StrtolowerValidator;

/**
 * Тестирует класс app\validators\StrtolowerValidator
 */
class StrtolowerValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_string = 'Corke PORKE rooper';
    private static $_expectedString = 'corke porke rooper';
    
    /**
     * Тестирует метод StrtolowerValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new MockModel();
        $model->name = self::$_string;
        
        $validator = new StrtolowerValidator();
        $validator->validateAttribute($model, 'name');
        
        $this->assertEquals(self::$_expectedString, $model->name);
    }
}
