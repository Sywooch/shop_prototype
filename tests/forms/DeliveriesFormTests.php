<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\DeliveriesForm;

/**
 * Тестирует класс DeliveriesForm
 */
class DeliveriesFormTests extends TestCase
{
    /**
     * Тестирует свойства DeliveriesForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(DeliveriesForm::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('description'));
        $this->assertTrue($reflection->hasProperty('price'));
    }
    
    /**
     * Тестирует метод DeliveriesForm::scenarios
     */
    public function testScenarios()
    {
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::DELETE]);
        $form->attributes = [
            'id'=>2,
        ];
        
        $this->assertSame(2, $form->id);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::CREATE]);
        $form->attributes = [
            'name'=>'Name',
            'description'=>'Description',
            'price'=>35.89,
        ];
        
        $this->assertSame('Name', $form->name);
        $this->assertSame('Description', $form->description);
        $this->assertSame(35.89, $form->price);
    }
    
    /**
     * Тестирует метод DeliveriesForm::rules
     */
    public function testRules()
    {
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::DELETE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::DELETE]);
        $form->attributes = [
            'id'=>1
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::CREATE]);
        $form->validate();
        
        $this->assertCount(3, $form->errors);
        
        $form = new DeliveriesForm(['scenario'=>DeliveriesForm::CREATE]);
        $form->attributes = [
            'name'=>'Name',
            'description'=>'Description',
            'price'=>35.89,
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
