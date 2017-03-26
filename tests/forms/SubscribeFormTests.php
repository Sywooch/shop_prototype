<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\SubscribeForm;

/**
 * Тестирует класс SubscribeForm
 */
class SubscribeFormTests extends TestCase
{
    /**
     * Тестирует свойства SubscribeForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SubscribeForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $this->assertTrue($reflection->hasProperty('email'));
    }
    
    /**
     * Тестирует метод SubscribeForm::scenarios
     */
    public function testScenarios()
    {
        $form = new SubscribeForm(['scenario'=>SubscribeForm::SAVE]);
        $form->attributes = [
            'email'=>'some@email.com',
        ];
        
        $this->assertSame('some@email.com', $form->email);
    }
    
    /**
     * Тестирует метод SubscribeForm::rules
     */
    public function testRules()
    {
        $form = new SubscribeForm(['scenario'=>SubscribeForm::SAVE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new SubscribeForm(['scenario'=>SubscribeForm::SAVE]);
        $form->attributes = [
            'email'=>'some@email.com',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
