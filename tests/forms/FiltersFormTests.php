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
        $this->assertTrue($reflection->hasProperty('category'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
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
            'category'=>'man',
            'subcategory'=>'shoes',
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
        
        $reflection = new \ReflectionProperty($form, 'category');
        $this->assertSame('man', $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'subcategory');
        $this->assertSame('shoes', $reflection->getValue($form));
        
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
            'url'=>'/shop/main-23',
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
            'url'=>'/shop/main-3',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
    
    /**
     * Тестирует метод FiltersForm::fields
     */
    public function testFields()
    {
        $form = new FiltersForm();
        $form->sortingField = 'name';
        $form->sortingType = SORT_ASC;
        $form->url = '/shop/main-7';
        $form->category = 'man';
        $form->subcategory = 'shoes';
        
        $result = $form->toArray();
        
        $this->assertSame('name', $result['sortingField']);
        $this->assertSame(SORT_ASC, $result['sortingType']);
        $this->assertSame([], $result['colors']);
        $this->assertSame([], $result['sizes']);
        $this->assertSame([], $result['brands']);
        $this->assertSame('/shop/main-7', $result['url']);
        $this->assertSame('man', $result['category']);
        $this->assertSame('shoes', $result['subcategory']);
    }
}
