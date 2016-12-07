<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\FiltersWidget;

/**
 * Тестирует класс FiltersWidget
 */
class FiltersWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(FiltersWidget::class);
        
        $this->assertTrue($reflection->hasProperty('colorsCollection'));
        $this->assertTrue($reflection->hasProperty('sizesCollection'));
        $this->assertTrue($reflection->hasProperty('brandsCollection'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
}
