<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\UsersFiltersForm;

/**
 * Тестирует класс UsersFiltersForm
 */
class UsersFiltersFormTests extends TestCase
{
    /**
     * Тестирует свойства UsersFiltersForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UsersFiltersForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('CLEAN'));
        
        $this->assertTrue($reflection->hasProperty('sortingField'));
        $this->assertTrue($reflection->hasProperty('sortingType'));
        $this->assertTrue($reflection->hasProperty('ordersStatus'));
        $this->assertTrue($reflection->hasProperty('url'));
    }
    
    /**
     * Тестирует метод UsersFiltersForm::scenarios
     */
    public function testScenarios()
    {
        $form = new UsersFiltersForm(['scenario'=>UsersFiltersForm::SAVE]);
        $form->attributes = [
            'sortingField'=>'received_date',
            'sortingType'=>SORT_ASC,
            'ordersStatus'=>1,
            'url'=>'https://shop.com',
        ];
        
        $reflection = new \ReflectionProperty($form, 'sortingField');
        $this->assertSame('received_date', $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'sortingType');
        $this->assertSame(SORT_ASC, $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'ordersStatus');
        $this->assertSame(1, $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'url');
        $this->assertSame('https://shop.com', $reflection->getValue($form));
        
        $form = new UsersFiltersForm(['scenario'=>UsersFiltersForm::CLEAN]);
        $form->attributes = [
            'url'=>'https://shop.com',
        ];
        
        $reflection = new \ReflectionProperty($form, 'url');
        $this->assertSame('https://shop.com', $reflection->getValue($form));
    }
    
    /**
     * Тестирует метод UsersFiltersForm::rules
     */
    public function testRules()
    {
        $form = new UsersFiltersForm(['scenario'=>UsersFiltersForm::SAVE]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new UsersFiltersForm(['scenario'=>UsersFiltersForm::SAVE]);
        $form->attributes = [
            'ordersStatus'=>'0',
            'url'=>'/shop/main-56',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        $this->assertSame(0, $form->ordersStatus);
        
        $form = new UsersFiltersForm(['scenario'=>UsersFiltersForm::CLEAN]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new UsersFiltersForm(['scenario'=>UsersFiltersForm::CLEAN]);
        $form->attributes = [
            'url'=>'/shop/main-5',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
