<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\BottomMenuWidget;

/**
 * Тестирует класс BottomMenuWidget
 */
class BottomMenuWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(BottomMenuWidget::class);
        
        $this->assertTrue($reflection->hasProperty('options'));
    }
    
    /**
     * Тестирует метод BottomMenuWidget::setItems
     */
    public function testSetItems()
    {
        $widget = new BottomMenuWidget();
        
        $reflection = new \ReflectionMethod($widget, 'setItems');
        $reflection->setAccessible(true);
        $reflection->invoke($widget);
        
        $reflection = new \ReflectionProperty($widget, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
    }
    
    /**
     * Тестирует метод BottomMenuWidget::run
     */
    public function testRun()
    {
        $widget = new BottomMenuWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}
