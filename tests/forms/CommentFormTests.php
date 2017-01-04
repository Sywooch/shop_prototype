<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\CommentForm;

/**
 * Тестирует класс CommentForm
 */
class CommentFormTests extends TestCase
{
    /**
     * Тестирует свойства CommentForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $this->assertTrue($reflection->hasProperty('text'));
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('id_product'));
        $this->assertTrue($reflection->hasProperty('seocode'));
    }
    
    /**
     * Тестирует метод CommentForm::scenarios
     */
    public function testScenarios()
    {
        $form = new CommentForm(['scenario'=>CommentForm::SAVE]);
        $form->attributes = [
            'text'=>'Text', 
            'name'=>'Name',
            'email'=>'mail@mail.com',
            'id_product'=>4,
            'seocode'=>'seocode',
        ];
        
        $reflection = new \ReflectionProperty($form, 'text');
        $result = $reflection->getValue($form);
        $this->assertSame('Text', $result);
        
        $reflection = new \ReflectionProperty($form, 'name');
        $result = $reflection->getValue($form);
        $this->assertSame('Name', $result);
        
        $reflection = new \ReflectionProperty($form, 'email');
        $result = $reflection->getValue($form);
        $this->assertSame('mail@mail.com', $result);
        
        $reflection = new \ReflectionProperty($form, 'id_product');
        $result = $reflection->getValue($form);
        $this->assertSame(4, $result);
        
        $reflection = new \ReflectionProperty($form, 'seocode');
        $result = $reflection->getValue($form);
        $this->assertSame('seocode', $result);
    }
    
    /**
     * Тестирует метод CommentForm::rules
     */
    public function testRules()
    {
        $form = new CommentForm(['scenario'=>CommentForm::SAVE]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(5, $form->errors);
        $this->assertArrayHasKey('text', $form->errors);
        $this->assertArrayHasKey('name', $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        $this->assertArrayHasKey('id_product', $form->errors);
        $this->assertArrayHasKey('seocode', $form->errors);
        
        $form = new CommentForm(['scenario'=>CommentForm::SAVE]);
        $form->attributes = [
            'text'=>'Text', 
            'name'=>'Name',
            'email'=>'mail@mail',
            'id_product'=>4,
            'seocode'=>'seocode',
        ];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('email', $form->errors);
        
        $form = new CommentForm(['scenario'=>CommentForm::SAVE]);
        $form->attributes = [
            'text'=>'Text', 
            'name'=>'Name',
            'email'=>'mail@mail.com',
            'id_product'=>4,
            'seocode'=>'seocode',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
