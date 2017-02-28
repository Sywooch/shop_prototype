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
        $this->assertTrue($reflection->hasConstant('GET'));
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('EDIT'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('text'));
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('id_product'));
        $this->assertTrue($reflection->hasProperty('active'));
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
        
        $form = new CommentForm(['scenario'=>CommentForm::GET]);
        $form->attributes = [
            'id'=>4,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(4, $result);
        
        $form = new CommentForm(['scenario'=>CommentForm::DELETE]);
        $form->attributes = [
            'id'=>7,
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(7, $result);
        
        $form = new CommentForm(['scenario'=>CommentForm::EDIT]);
        $form->attributes = [
            'id'=>7,
            'text'=>'Text',
            'active'=>1
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(7, $result);
        
        $reflection = new \ReflectionProperty($form, 'text');
        $result = $reflection->getValue($form);
        $this->assertSame('Text', $result);
        
        $reflection = new \ReflectionProperty($form, 'active');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
    }
    
    /**
     * Тестирует метод CommentForm::rules
     */
    public function testRules()
    {
        $form = new CommentForm(['scenario'=>CommentForm::SAVE]);
        $form->validate();
        
        $this->assertCount(4, $form->errors);
        
        $form = new CommentForm(['scenario'=>CommentForm::SAVE]);
        $form->attributes = [
            'text'=>'Text', 
            'name'=>'Name',
            'email'=>'mail@mail',
            'id_product'=>4,
        ];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CommentForm(['scenario'=>CommentForm::SAVE]);
        $form->attributes = [
            'text'=>'Text', 
            'name'=>'Name',
            'email'=>'mail@mail.com',
            'id_product'=>4,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new CommentForm(['scenario'=>CommentForm::GET]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CommentForm(['scenario'=>CommentForm::GET]);
        $form->attributes = [
            'id'=>22,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new CommentForm(['scenario'=>CommentForm::DELETE]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CommentForm(['scenario'=>CommentForm::DELETE]);
        $form->attributes = [
            'id'=>17,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new CommentForm(['scenario'=>CommentForm::EDIT]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(2, $form->errors);
        
        $form = new CommentForm(['scenario'=>CommentForm::EDIT]);
        $form->attributes = [
            'id'=>7,
            'text'=>'Text',
        ];
        
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
