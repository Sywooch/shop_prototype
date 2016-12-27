<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\UserLoginForm;

/**
 * Тестирует класс UserLoginForm
 */
class UserLoginFormTests extends TestCase
{
    /**
     * Тестирует свойства UserLoginForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserLoginForm::class);
        
        $this->assertTrue($reflection->hasConstant('GET'));
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('password'));
    }
    
    /**
     * Тестирует метод UserLoginForm::scenarios
     */
    public function testScenarios()
    {
        $form = new UserLoginForm(['scenario'=>UserLoginForm::GET]);
        $form->attributes = [
            'email'=>'some@some.com',
            'password'=>'hGju97Uy'
        ];
        
        $reflection = new \ReflectionProperty($form, 'email');
        $result = $reflection->getValue($form);
        $this->assertSame('some@some.com', $result);
        
        $reflection = new \ReflectionProperty($form, 'password');
        $result = $reflection->getValue($form);
        $this->assertSame('hGju97Uy', $result);
    }
    
    /**
     * Тестирует метод UserLoginForm::rules
     */
    public function testRules()
    {
        $form = new UserLoginForm(['scenario'=>UserLoginForm::GET]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(2, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        $this->assertArrayHasKey('password', $form->errors);
        
        
        $form = new UserLoginForm(['scenario'=>UserLoginForm::GET]);
        $form->attributes = [
            'email'=>'some',
            'password'=>'hGju97Uy'
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new UserLoginForm(['scenario'=>UserLoginForm::GET]);
        $form->attributes = [
            'email'=>'some@some.com',
            'password'=>'hGju97Uy'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
