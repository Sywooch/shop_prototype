<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\CurrencyForm;

/**
 * Тестирует класс CurrencyForm
 */
class CurrencyFormTests extends TestCase
{
    /**
     * Тестирует свойства CurrencyForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencyForm::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('code'));
        $this->assertTrue($reflection->hasProperty('main'));
    }
    
    /**
     * Тестирует метод CurrencyForm::scenarios
     */
    public function testScenarios()
    {
        $form = new CurrencyForm(['scenario'=>CurrencyForm::DELETE]);
        $form->attributes = [
            'id'=>2,
        ];
        
        $this->assertSame(2, $form->id);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::CREATE]);
        $form->attributes = [
            'code'=>'CODE',
            'main'=>1,
        ];
        
        $this->assertSame('CODE', $form->code);
        $this->assertSame(1, $form->main);
    }
    
    /**
     * Тестирует метод CurrencyForm::rules
     */
    public function testRules()
    {
        $form = new CurrencyForm(['scenario'=>CurrencyForm::DELETE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::DELETE]);
        $form->attributes = [
            'id'=>1
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::CREATE]);
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new CurrencyForm(['scenario'=>CurrencyForm::CREATE]);
        $form->attributes = [
            'code'=>'CODE'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
