<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\PurchaseForm;

/**
 * Тестирует класс PurchaseForm
 */
class PurchaseFormTests extends TestCase
{
    /**
     * Тестирует свойства PurchaseForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchaseForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $this->assertTrue($reflection->hasProperty('quantity'));
        $this->assertTrue($reflection->hasProperty('id_color'));
        $this->assertTrue($reflection->hasProperty('id_size'));
        $this->assertTrue($reflection->hasProperty('id_product'));
        $this->assertTrue($reflection->hasProperty('price'));
    }
    
    /**
     * Тестирует метод PurchaseForm::scenarios
     */
    public function testScenarios()
    {
        $form = new PurchaseForm(['scenario'=>PurchaseForm::SAVE]);
        $form->attributes = [
            'quantity'=>1, 
            'id_color'=>3,
            'id_size'=>2,
            'id_product'=>4,
            'price'=>346.86
        ];
        
        $reflection = new \ReflectionProperty($form, 'quantity');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
        
        $reflection = new \ReflectionProperty($form, 'id_color');
        $result = $reflection->getValue($form);
        $this->assertSame(3, $result);
        
        $reflection = new \ReflectionProperty($form, 'id_size');
        $result = $reflection->getValue($form);
        $this->assertSame(2, $result);
        
        $reflection = new \ReflectionProperty($form, 'id_product');
        $result = $reflection->getValue($form);
        $this->assertSame(4, $result);
        
        $reflection = new \ReflectionProperty($form, 'price');
        $result = $reflection->getValue($form);
        $this->assertSame(346.86, $result);
    }
    
    /**
     * Тестирует метод PurchaseForm::rules
     */
    public function testRules()
    {
        $form = new PurchaseForm(['scenario'=>PurchaseForm::SAVE]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(5, $form->errors);
        $this->assertArrayHasKey('quantity', $form->errors);
        $this->assertArrayHasKey('id_color', $form->errors);
        $this->assertArrayHasKey('id_size', $form->errors);
        $this->assertArrayHasKey('id_product', $form->errors);
        $this->assertArrayHasKey('price', $form->errors);
        
        $form = new PurchaseForm(['scenario'=>PurchaseForm::SAVE]);
        $form->attributes = [
            'quantity'=>1, 
            'id_color'=>3,
            'id_size'=>2,
            'id_product'=>4,
            'price'=>346.86
        ];
        
        $this->assertEmpty($form->errors);
    }
}
