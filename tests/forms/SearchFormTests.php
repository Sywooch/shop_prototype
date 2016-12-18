<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\SearchForm;

/**
 * Тестирует класс SearchForm
 */
class SearchFormTests extends TestCase
{
    /**
     * Тестирует свойства SearchForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SearchForm::class);
        
        $this->assertTrue($reflection->hasConstant('GET'));
        
        $this->assertTrue($reflection->hasProperty('text'));
        $this->assertTrue($reflection->hasProperty('url'));
    }
    
    /**
     * Тестирует метод SearchForm::scenarios
     */
    public function testScenarios()
    {
        $form = new SearchForm(['scenario'=>SearchForm::GET]);
        $form->attributes = [
            'text'=>'Some text',
            'url'=>'http:://shop.com'
        ];
        
        $reflection = new \ReflectionProperty($form, 'text');
        $result = $reflection->getValue($form);
        $this->assertSame('Some text', $result);
        
        $reflection = new \ReflectionProperty($form, 'url');
        $result = $reflection->getValue($form);
        $this->assertSame('http:://shop.com', $result);
    }
    
    /**
     * Тестирует метод SearchForm::rules
     */
    public function testRules()
    {
        $form = new SearchForm(['scenario'=>SearchForm::GET]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(2, $form->errors);
        $this->assertArrayHasKey('text', $form->errors);
        $this->assertArrayHasKey('url', $form->errors);
        
        $form = new SearchForm(['scenario'=>SearchForm::GET]);
        $form->attributes = [
            'text'=>'Some text',
            'url'=>'http:://shop.com'
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
