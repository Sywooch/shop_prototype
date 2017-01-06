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
     * Тестирует свойства ChangeCurrencyForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ChangeCurrencyForm::class);
        
        $this->assertTrue($reflection->hasConstant('SET'));
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('url'));
    }
    
    /**
     * Тестирует метод ChangeCurrencyForm::scenarios
     */
    public function testScenarios()
    {
        $form = new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::SET]);
        $form->attributes = [
            'id'=>1,
            'url'=>'some@some.com'
        ];
        
        $reflection = new \ReflectionProperty($form, 'id');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
        
        $reflection = new \ReflectionProperty($form, 'url');
        $result = $reflection->getValue($form);
        $this->assertSame('some@some.com', $result);
    }
    
    /**
     * Тестирует метод ChangeCurrencyForm::rules
     */
    public function testRules()
    {
        $form = new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::SET]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(2, $form->errors);
        $this->assertArrayHasKey('id', $form->errors);
        $this->assertArrayHasKey('url', $form->errors);
        
        $form = new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::SET]);
        $form->attributes = [
            'id'=>1,
            'url'=>'some@some.com'
        ];
        
        $this->assertEmpty($form->errors);
    }
}
