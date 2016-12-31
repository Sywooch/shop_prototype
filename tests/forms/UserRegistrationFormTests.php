<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\UserRegistrationForm;

/**
 * Тестирует класс UserRegistrationForm
 */
class UserRegistrationFormTests extends TestCase
{
    
    /**
     * Тестирует свойства UserRegistrationForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserRegistrationForm::class);
        
        $this->assertTrue($reflection->hasConstant('REGISTRATION'));
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('password'));
        $this->assertTrue($reflection->hasProperty('password2'));
    }
    
    /**
     * Тестирует метод UserRegistrationForm::scenarios
     */
    public function testScenarios()
    {
        $form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
        $form->attributes = [
            'email'=>'some@some.com',
            'password'=>'password',
            'password2'=>'password'
        ];
        
        $reflection = new \ReflectionProperty($form, 'email');
        $result = $reflection->getValue($form);
        $this->assertSame('some@some.com', $result);
        
        $reflection = new \ReflectionProperty($form, 'password');
        $result = $reflection->getValue($form);
        $this->assertSame('password', $result);
        
        $reflection = new \ReflectionProperty($form, 'password2');
        $result = $reflection->getValue($form);
        $this->assertSame('password', $result);
    }
    
    /**
     * Тестирует метод UserRegistrationForm::rules
     */
    public function testRules()
    {
        $form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(3, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        $this->assertArrayHasKey('password', $form->errors);
        $this->assertArrayHasKey('password2', $form->errors);
        
        $form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
        $form->attributes = [
            'email'=>'some@some',
            'password'=>'password',
            'password2'=>'password'
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
        $form->attributes = [
            'email'=>'some@some.com',
            'password'=>'password',
            'password2'=>'password'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
