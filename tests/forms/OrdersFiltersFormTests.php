<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\OrdersFiltersForm;

/**
 * Тестирует класс OrdersFiltersForm
 */
class OrdersFiltersFormTests extends TestCase
{
    /**
     * Тестирует свойства OrdersFiltersForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(OrdersFiltersForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('CLEAN'));
        
        $this->assertTrue($reflection->hasProperty('sortingField'));
        $this->assertTrue($reflection->hasProperty('sortingType'));
        $this->assertTrue($reflection->hasProperty('status'));
        $this->assertTrue($reflection->hasProperty('dateFrom'));
        $this->assertTrue($reflection->hasProperty('dateTo'));
        $this->assertTrue($reflection->hasProperty('url'));
    }
    
    /**
     * Тестирует метод OrdersFiltersForm::scenarios
     */
    public function testScenarios()
    {
        $form = new OrdersFiltersForm(['scenario'=>OrdersFiltersForm::SAVE]);
        $form->attributes = [
            'sortingField'=>'received_date',
            'sortingType'=>SORT_ASC,
            'status'=>'shipped',
            'dateFrom'=>time(),
            'dateTo'=>time(),
            'url'=>'https://shop.com',
        ];
        
        $reflection = new \ReflectionProperty($form, 'sortingField');
        $this->assertSame('received_date', $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'sortingType');
        $this->assertSame(SORT_ASC, $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'status');
        $this->assertSame('shipped', $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'dateFrom');
        $this->assertSame(time(), $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'dateTo');
        $this->assertSame(time(), $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'url');
        $this->assertSame('https://shop.com', $reflection->getValue($form));
        
        $form = new OrdersFiltersForm(['scenario'=>OrdersFiltersForm::CLEAN]);
        $form->attributes = [
            'url'=>'https://shop.com',
        ];
        
        $reflection = new \ReflectionProperty($form, 'url');
        $this->assertSame('https://shop.com', $reflection->getValue($form));
    }
    
    /**
     * Тестирует метод OrdersFiltersForm::rules
     */
    public function testRules()
    {
        $form = new OrdersFiltersForm(['scenario'=>OrdersFiltersForm::SAVE]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new OrdersFiltersForm(['scenario'=>OrdersFiltersForm::SAVE]);
        $form->attributes = [
            'url'=>'/shop/sneakers',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
        
        $form = new OrdersFiltersForm(['scenario'=>OrdersFiltersForm::CLEAN]);
        $form->attributes = [];
        $form->validate();
        
        $this->assertCount(1, $form->errors);
        
        $form = new OrdersFiltersForm(['scenario'=>OrdersFiltersForm::CLEAN]);
        $form->attributes = [
            'url'=>'/shop/coats',
        ];
        $form->validate();
        
        $this->assertEmpty($form->errors);
    }
}
