<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\ActiveStatusExistsValidator;

/**
 * Тестирует класс ActiveStatusExistsValidator
 */
class ActiveStatusExistsValidatorTests extends TestCase
{
    private $validator;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new ActiveStatusExistsValidator();
    }
    
    /**
     * Тестирует метод ActiveStatusExistsValidator::validateAttribute
     * @expectedException ErrorException
     * @expectedExceptionMessage Недопустимый диапазон данных: field
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $field = 'fake';
        };
        
        $this->validator->validateAttribute($model, 'field');
    }
}
