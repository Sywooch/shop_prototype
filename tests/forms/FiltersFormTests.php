<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\FiltersForm;

/**
 * Тестирует класс FiltersForm
 */
class FiltersFormTests extends TestCase
{
    /**
     * Тестирует наличие свойств и констант
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(FiltersForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('CLEAN'));
        
        $this->assertTrue($reflection->hasProperty('sortingField'));
        $this->assertTrue($reflection->hasProperty('sortingType'));
        $this->assertTrue($reflection->hasProperty('colors'));
        $this->assertTrue($reflection->hasProperty('sizes'));
        $this->assertTrue($reflection->hasProperty('brands'));
        $this->assertTrue($reflection->hasProperty('url'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $form = new FiltersForm(['scenario'=>FiltersForm::SAVE]);
        $form->attributes = [
            'sortingField'=>'price',
            'sortingType'=>'SORT_ASC',
            'colors'=>[12, 4],
            'sizes'=>[3, 7],
            'brands'=>2,
            'url'=>'http://shop.com',
        ];
        
        $reflection = new \ReflectionProperty($form, 'sortingField');
        $result = $reflection->getValue($form);
        $this->assertSame('price', $result);
        
        $reflection = new \ReflectionProperty($form, 'sortingType');
        $result = $reflection->getValue($form);
        $this->assertSame('SORT_ASC', $result);
        
        $reflection = new \ReflectionProperty($form, 'colors');
        $result = $reflection->getValue($form);
        $this->assertSame([12, 4], $result);
        
        $reflection = new \ReflectionProperty($form, 'sizes');
        $result = $reflection->getValue($form);
        $this->assertSame([3, 7], $result);
        
        $reflection = new \ReflectionProperty($form, 'brands');
        $result = $reflection->getValue($form);
        $this->assertSame(2, $result);
        
        $reflection = new \ReflectionProperty($form, 'url');
        $result = $reflection->getValue($form);
        $this->assertSame('http://shop.com', $result);
        
        $form = new FiltersForm(['scenario'=>FiltersForm::CLEAN]);
        $form->attributes = ['url'=>'http://shop.com'];
        
        $reflection = new \ReflectionProperty($form, 'url');
        $result = $reflection->getValue($form);
        $this->assertSame('http://shop.com', $result);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $form = new FiltersForm(['scenario'=>FiltersForm::SAVE]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('url', $form->errors);
        
        $form = new FiltersForm(['scenario'=>FiltersForm::SAVE]);
        $form->attributes = ['url'=>'http://shop.com'];
        
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new FiltersForm(['scenario'=>FiltersForm::CLEAN]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('url', $form->errors);
        
        $form = new FiltersForm(['scenario'=>FiltersForm::CLEAN]);
        $form->attributes = ['url'=>'http://shop.com'];
        
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
