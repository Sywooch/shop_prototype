<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\SortingTypeExistsValidator;

/**
 * Тестирует класс SortingTypeExistsValidator
 */
class SortingTypeExistsValidatorTests extends TestCase
{
    private $validator;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new SortingTypeExistsValidator();
    }
    
    /**
     * Тестирует метод SortingTypeExistsValidator::validateAttribute
     * @expectedException ErrorException
     * @expectedExceptionMessage Недопустимый диапазон данных: sortingType
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $sortingType = 25;
        };
        
        $this->validator->validateAttribute($model, 'sortingType');
    }
}
