<?php

namespace app\tests\forms;

use PHPUnit\Framework\TestCase;
use app\forms\AdminOrdersFiltersForm;

/**
 * Тестирует класс AdminOrdersFiltersForm
 */
class AdminOrdersFiltersFormTests extends TestCase
{
    /**
     * Тестирует свойства AdminOrdersFiltersForm
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrdersFiltersForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('CLEAN'));
        
        $this->assertTrue($reflection->hasProperty('sortingField'));
        $this->assertTrue($reflection->hasProperty('sortingType'));
        $this->assertTrue($reflection->hasProperty('status'));
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersForm::scenarios
     */
    public function testScenarios()
    {
        $form = new AdminOrdersFiltersForm(['scenario'=>AdminOrdersFiltersForm::SAVE]);
        $form->attributes = [
            'sortingField'=>'received_date',
            'sortingType'=>SORT_ASC,
            'status'=>'shipped',
        ];
        
        $reflection = new \ReflectionProperty($form, 'sortingField');
        $this->assertSame('received_date', $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'sortingType');
        $this->assertSame(SORT_ASC, $reflection->getValue($form));
        
        $reflection = new \ReflectionProperty($form, 'status');
        $this->assertSame('shipped', $reflection->getValue($form));
    }
}
