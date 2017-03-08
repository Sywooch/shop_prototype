<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\SortingFieldsUsersExistsValidator;

/**
 * Тестирует класс SortingFieldsUsersExistsValidator
 */
class SortingFieldsUsersExistsValidatorTests extends TestCase
{
    private $validator;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new SortingFieldsUsersExistsValidator();
    }
    
    /**
     * Тестирует метод SortingFieldsUsersExistsValidator::validateAttribute
     * @expectedException ErrorException
     * @expectedExceptionMessage Недопустимый диапазон данных: sortingField
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $sortingField = 'fake';
        };
        
        $this->validator->validateAttribute($model, 'sortingField');
    }
}
