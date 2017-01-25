<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\OrderStatusForm;

/**
 * Тестирует класс OrderStatusForm
 */
class OrderStatusFormTests extends TestCase
{
    /**
     * Тестирует свойства OrderStatusForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(OrderStatusForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('status'));
    }
    
    /**
     * Тестирует метод OrderStatusForm::scenarios
     */
    public function testScenarios()
    {
        $form = new OrderStatusForm(['scenario'=>OrderStatusForm::SAVE]);
        $form->attributes = [
            'id'=>23,
            'status'=>3
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(23, $result);
        
        $reflection = new \ReflectionProperty($form, 'status');
        $result = $reflection->getValue($form);
        $this->assertSame(3, $result);
    }
    
    /**
     * Тестирует метод OrderStatusForm::rules
     */
    public function testRules()
    {
        $form = new OrderStatusForm(['scenario'=>OrderStatusForm::SAVE]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(2, $form->errors);
        $this->assertArrayHasKey('id', $form->errors);
        $this->assertArrayHasKey('status', $form->errors);
        
        $form = new OrderStatusForm(['scenario'=>OrderStatusForm::SAVE]);
        $form->attributes = [
            'id'=>23,
            'status'=>3
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
