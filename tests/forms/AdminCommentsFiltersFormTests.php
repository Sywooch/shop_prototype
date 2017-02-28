<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\AdminCommentsFiltersForm;

/**
 * Тестирует класс AdminCommentsFiltersForm
 */
class AdminCommentsFiltersFormTests extends TestCase
{
    /**
     * Тестирует свойства AdminCommentsFiltersForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCommentsFiltersForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('CLEAN'));
        
        $this->assertTrue($reflection->hasProperty('sortingField'));
        $this->assertTrue($reflection->hasProperty('sortingType'));
        $this->assertTrue($reflection->hasProperty('activeStatus'));
        $this->assertTrue($reflection->hasProperty('url'));
    }
    
    /**
     * Тестирует метод AdminCommentsFiltersForm::scenarios
     */
    public function testScenarios()
    {
        $form = new AdminCommentsFiltersForm(['scenario'=>AdminCommentsFiltersForm::SAVE]);
        $form->attributes = [
            'sortingField'=>'received_date',
            'sortingType'=>SORT_ASC,
            'activeStatus'=>1,
            'url'=>'https://shop.com',
        ];
        
        $reflection = new \ReflectionProperty($form, 'sortingField');
        $this->assertSame('received_date', $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'sortingType');
        $this->assertSame(SORT_ASC, $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'activeStatus');
        $this->assertSame(1, $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'url');
        $this->assertSame('https://shop.com', $reflection->getValue($form));
        
        $form = new AdminCommentsFiltersForm(['scenario'=>AdminCommentsFiltersForm::CLEAN]);
        $form->attributes = [
            'url'=>'https://shop.com',
        ];
        
        $reflection = new \ReflectionProperty($form, 'url');
        $this->assertSame('https://shop.com', $reflection->getValue($form));
    }
    
    /**
     * Тестирует метод AdminCommentsFiltersForm::rules
     */
    public function testRules()
    {
        $form = new AdminCommentsFiltersForm(['scenario'=>AdminCommentsFiltersForm::SAVE]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new AdminCommentsFiltersForm(['scenario'=>AdminCommentsFiltersForm::SAVE]);
        $form->attributes = [
            'activeStatus'=>'0',
            'url'=>'https://shop.com',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        $this->assertSame(0, $form->activeStatus);
        
        $form = new AdminCommentsFiltersForm(['scenario'=>AdminCommentsFiltersForm::CLEAN]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new AdminCommentsFiltersForm(['scenario'=>AdminCommentsFiltersForm::CLEAN]);
        $form->attributes = [
            'url'=>'https://shop.com',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
