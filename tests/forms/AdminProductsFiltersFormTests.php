<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\AdminProductsFiltersForm;

/**
 * Тестирует класс AdminProductsFiltersForm
 */
class AdminProductsFiltersFormTests extends TestCase
{
    /**
     * Тестирует свойства AdminProductsFiltersForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductsFiltersForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('CLEAN'));
        
        $this->assertTrue($reflection->hasProperty('sortingField'));
        $this->assertTrue($reflection->hasProperty('sortingType'));
        $this->assertTrue($reflection->hasProperty('colors'));
        $this->assertTrue($reflection->hasProperty('sizes'));
        $this->assertTrue($reflection->hasProperty('brands'));
        $this->assertTrue($reflection->hasProperty('categories'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
        $this->assertTrue($reflection->hasProperty('active'));
        $this->assertTrue($reflection->hasProperty('url'));
    }
    
    /**
     * Тестирует метод AdminProductsFiltersForm::scenarios
     */
    public function testScenarios()
    {
        $form = new AdminProductsFiltersForm(['scenario'=>AdminProductsFiltersForm::SAVE]);
        $form->attributes = [
            'sortingField'=>'received_date',
            'sortingType'=>SORT_ASC,
            'colors'=>[1,2],
            'sizes'=>[1,3],
            'brands'=>[1],
            'categories'=>[1,2],
            'subcategory'=>[2],
            'active'=>true,
            'url'=>'https://shop.com',
        ];
        
        $reflection = new \ReflectionProperty($form, 'sortingField');
        $this->assertSame('received_date', $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'sortingType');
        $this->assertSame(SORT_ASC, $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'colors');
        $this->assertSame([1,2], $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'sizes');
        $this->assertSame([1,3], $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'brands');
        $this->assertSame([1], $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'categories');
        $this->assertSame([1,2], $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'subcategory');
        $this->assertSame([2], $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'active');
        $this->assertSame(true, $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'url');
        $this->assertSame('https://shop.com', $reflection->getValue($form));
        
        $form = new AdminProductsFiltersForm(['scenario'=>AdminProductsFiltersForm::CLEAN]);
        $form->attributes = [
            'url'=>'https://shop.com',
        ];
        
        $reflection = new \ReflectionProperty($form, 'url');
        $this->assertSame('https://shop.com', $reflection->getValue($form));
    }
    
    /**
     * Тестирует метод AdminProductsFiltersForm::rules
     */
    public function testRules()
    {
        $form = new AdminProductsFiltersForm(['scenario'=>AdminProductsFiltersForm::SAVE]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new AdminProductsFiltersForm(['scenario'=>AdminProductsFiltersForm::SAVE]);
        $form->attributes = [
            'url'=>'https://shop.com',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new AdminProductsFiltersForm(['scenario'=>AdminProductsFiltersForm::CLEAN]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new AdminProductsFiltersForm(['scenario'=>AdminProductsFiltersForm::CLEAN]);
        $form->attributes = [
            'url'=>'https://shop.com',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
