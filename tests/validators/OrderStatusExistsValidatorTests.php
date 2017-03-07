<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\OrderStatusExistsValidator;

/**
 * Тестирует класс OrderStatusExistsValidator
 */
class OrderStatusExistsValidatorTests extends TestCase
{
    private $validator;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new OrderStatusExistsValidator();
    }
    
    /**
     * Тестирует метод OrderStatusExistsValidator::validateAttribute
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
