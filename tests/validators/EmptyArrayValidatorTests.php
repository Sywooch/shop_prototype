<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\EmptyArrayValidator;
use yii\base\Model;

/**
 * Тестирует класс EmptyArrayValidator
 */
class EmptyArrayValidatorTests extends TestCase
{
    /**
    * Тестирует метод EmptyArrayValidator::validateAttribute
    */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $items = [];
        };
        
        $validator = new EmptyArrayValidator();
        $validator->validateAttribute($model, 'items');
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(1, $model->errors);
        $this->assertArrayHasKey('items', $model->errors);
        
        $model = new class() extends Model {
            public $items = [1];
        };
        
        $validator = new EmptyArrayValidator();
        $validator->validateAttribute($model, 'items');
        
        $this->assertEmpty($model->errors);
    }
}
