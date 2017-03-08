<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\validators\SeparateEmailValidator;

/**
 * Тестирует класс SeparateEmailValidator
 */
class SeparateEmailValidatorTests extends TestCase
{
    /**
     * Тестирует метод SeparateEmailValidator::validate
     * @expectedException ErrorException
     * @expectedExceptionMessage Получен неверный тип данных вместо: some@mail
     */
    public function testValidate()
    {
        $validator = new SeparateEmailValidator();
        $validator->validate('some@mail');
    }
    
    /**
     * Тестирует метод SeparateEmailValidator::validate
     */
    public function testValidateValid()
    {
        $validator = new SeparateEmailValidator();
        $result = $validator->validate('some@mail.com');
        
        $this->assertSame('some@mail.com', $result);
    }
}
