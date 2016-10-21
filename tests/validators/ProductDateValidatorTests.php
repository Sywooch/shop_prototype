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
        
        $this->assertFalse(empty($model->date));
        $this->assertEquals(10, strlen($model->date));
        
        $currentDateTime = new \DateTime();
        $currentDateTime->setTimestamp(time());
        $expectedDate = $currentDateTime->format('d.m.Y');
        
        $modelDateTime = new \DateTime();
        $modelDateTime->setTimestamp($model->date);
        $modelDate = $modelDateTime->format('d.m.Y');
        
        $this->assertEquals($expectedDate, $modelDate);
    }
}
