<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\ChangeCurrencyForm;

/**
 * Тестирует класс ChangeCurrencyForm
 */
class ChangeCurrencyFormTests extends TestCase
{
    /**
     * Тестирует наличие свойств и констант
     */
    public function testProperty()
    {
        $reflection = new \ReflectionClass(ChangeCurrencyForm::class);
        
        $this->assertTrue($reflection->hasConstant('CHANGE'));
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('url'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $form = new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::CHANGE]);
        $form->attributes = [
            'id'=>1,
            'url'=>'http://shop.com',
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
        
        $reflection = new \ReflectionProperty($form, 'url');
        $result = $reflection->getValue($form);
        $this->assertSame('http://shop.com', $result);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $form = new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::CHANGE]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(2, $form->errors);
        $this->assertArrayHasKey('id', $form->errors);
        $this->assertArrayHasKey('url', $form->errors);
        
        $form = new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::CHANGE]);
        $form->attributes = [
            'id'=>1,
            'url'=>'http://shop.com',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
