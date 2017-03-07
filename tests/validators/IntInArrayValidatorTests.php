<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\IntInArrayValidator;

/**
 * Тестирует класс IntInArrayValidator
 */
class IntInArrayValidatorTests extends TestCase
{
    private $validator;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new IntInArrayValidator();
    }
    
    /**
     * Тестирует метод IntInArrayValidator::validateAttribute
     * @expectedException ErrorException
     * @expectedExceptionMessage Недопустимый диапазон данных: field
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $field = [25, 'a'];
        };
        
        $this->validator->validateAttribute($model, 'field');
    }
}
