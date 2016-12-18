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
     * Тестирует свойства FiltersForm
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
     * Тестирует метод FiltersForm::scenarios
     */
    public function testScenarios()
    {
        $form = new FiltersForm(['scenario'=>FiltersForm::SAVE]);
        $form->attributes = [
            'sortingField'=>'name',
            'sortingType'=>SORT_ASC,
            'colors'=>[1, 4],
            'sizes'=>[2, 3],
            'brands'=>[1],
            'url'=>'https://shop.com',
        ];
        
        $reflection = new \ReflectionProperty($form, 'sortingField');
        $this->assertSame('name', $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'sortingType');
        $this->assertSame(SORT_ASC, $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'colors');
        $this->assertSame([1, 4], $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'sizes');
        $this->assertSame([2, 3], $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'brands');
        $this->assertSame([1], $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'url');
        $this->assertSame('https://shop.com', $reflection->getValue($form));
        
        $form = new FiltersForm(['scenario'=>FiltersForm::CLEAN]);
        $form->attributes = [
            'url'=>'https://shop.com',
        ];
        
        $reflection = new \ReflectionProperty($form, 'url');
        $this->assertSame('https://shop.com', $reflection->getValue($form));
    }
    
    /**
     * Тестирует метод FiltersForm::rules
     */
    public function testRules()
    {
        $form = new FiltersForm(['scenario'=>FiltersForm::SAVE]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('url', $form->errors);
        
        $form = new FiltersForm(['scenario'=>FiltersForm::SAVE]);
        $form->attributes = [
            'url'=>'https://shop.com',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new FiltersForm(['scenario'=>FiltersForm::CLEAN]);
        $form->validate();
        
        $this->assertNotEmpty($form->errors);
        $this->assertCount(1, $form->errors);
        $this->assertArrayHasKey('url', $form->errors);
        
        $form = new FiltersForm(['scenario'=>FiltersForm::CLEAN]);
        $form->attributes = [
            'url'=>'https://shop.com',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
