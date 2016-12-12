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
     * Тестирует наличие свойств и констант
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchaseForm::class);
        
        $this->assertTrue($reflection->hasConstant('ADD'));
        
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
        $form = new PurchaseForm(['scenario'=>PurchaseForm::ADD]);
        $form->attributes = [
            'quantity'=>3,
            'id_color'=>2,
            'id_size'=>34,
            'id_product'=>56,
            'price'=>1965.78
        ];
        
        $reflection = new \ReflectionProperty($form, 'quantity');
        $this->assertSame(3, $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'id_color');
        $this->assertSame(2, $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'id_size');
        $this->assertSame(34, $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'id_product');
        $this->assertSame(56, $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'price');
        $this->assertSame(1965.78, $reflection->getValue($form));
    }
    
    /**
     * Тестирует метод PurchaseForm::rules
     */
    public function testRules()
    {
        $form = new PurchaseForm(['scenario'=>PurchaseForm::ADD]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(5, $form->errors);
        $this->assertArrayHasKey('quantity', $form->errors);
        $this->assertArrayHasKey('id_color', $form->errors);
        $this->assertArrayHasKey('id_size', $form->errors);
        $this->assertArrayHasKey('id_product', $form->errors);
        $this->assertArrayHasKey('price', $form->errors);
        
        $form = new PurchaseForm(['scenario'=>PurchaseForm::ADD]);
        $form->attributes = [
            'quantity'=>3,
            'id_color'=>2,
            'id_size'=>34,
            'id_product'=>56,
            'price'=>1965.78
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
