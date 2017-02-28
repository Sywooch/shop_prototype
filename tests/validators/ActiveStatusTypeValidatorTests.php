<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\ActiveStatusTypeValidator;

/**
 * Тестирует класс ActiveStatusTypeValidator
 */
class ActiveStatusTypeValidatorTests extends TestCase
{
    private $validator;
    
    public function setUp()
    {
        $this->validator = new ActiveStatusTypeValidator();
    }
    
    /**
     * Тестирует метод ActiveStatusTypeValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $status = '0';
        };
        
        $this->assertTrue(is_string($model->status));
        $this->assertSame((string) 0, $model->status);
        
        $this->validator->validateAttribute($model, 'status');
        
        $this->assertTrue(is_int($model->status));
        $this->assertSame((int) 0, $model->status);
        
        $model = new class() extends Model {
            public $status = '';
        };
        
        $this->assertTrue(is_string($model->status));
        
        $this->validator->validateAttribute($model, 'status');
        
        $this->assertFalse(is_int($model->status));
    }
}
