<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\MailingForm;

/**
 * Тестирует класс MailingForm
 */
class MailingFormTests extends TestCase
{
    /**
     * Тестирует свойства MailingForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('email'));
    }
    
    /**
     * Тестирует метод MailingForm::scenarios
     */
    public function testScenarios()
    {
        $form = new MailingForm(['scenario'=>MailingForm::SAVE]);
        $form->attributes = [
            'id'=>1,
            'email'=>'some@some.com'
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertEquals(1, $result);
        
        $reflection = new \ReflectionProperty($form, 'email');
        $result = $reflection->getValue($form);
        $this->assertEquals('some@some.com', $result);
    }
    
    /**
     * Тестирует метод MailingForm::rules
     */
    public function testRules()
    {
        $form = new MailingForm(['scenario'=>MailingForm::SAVE]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(2, $form->errors);
        $this->assertArrayHasKey('id', $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new MailingForm(['scenario'=>MailingForm::SAVE]);
        $form->attributes = [
            'id'=>1,
            'email'=>'some@some'
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new MailingForm(['scenario'=>MailingForm::SAVE]);
        $form->attributes = [
            'id'=>1,
            'email'=>'some@some.com'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}