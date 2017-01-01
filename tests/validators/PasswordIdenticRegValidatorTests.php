<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\PasswordIdenticRegValidator;
use yii\base\Model;

/**
 * Тестирует класс PasswordIdenticRegValidator
 */
class PasswordIdenticRegValidatorTests extends TestCase
{
    /**
     * Тестирует метод PasswordIdenticRegValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        # Если пароли не совпадают
        
        $model = new class() extends Model {
            public $password = 'password1';
            public $password2 = 'password2';
        };
        
        $validator = new PasswordIdenticRegValidator();
        $validator->validateAttribute($model, 'password2');
        
        $this->assertNotEmpty($model->errors);
        $this->assertArrayHasKey('password2', $model->errors);
        
        # Если пароли совпадают
        
        $model = new class() extends Model {
            public $password = 'password1';
            public $password2 = 'password1';
        };
        
        $validator = new PasswordIdenticRegValidator();
        $validator->validateAttribute($model, 'password2');
        
        $this->assertEmpty($model->errors);
    }
}
