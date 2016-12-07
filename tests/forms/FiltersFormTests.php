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
    public function testProperty()
    {
        $reflection = new \ReflectionClass(FiltersForm::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $this->assertTrue($reflection->hasProperty('sortingField'));
        $this->assertTrue($reflection->hasProperty('sortingType'));
        $this->assertTrue($reflection->hasProperty('colors'));
        $this->assertTrue($reflection->hasProperty('sizes'));
        $this->assertTrue($reflection->hasProperty('brands'));
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
    }
}
