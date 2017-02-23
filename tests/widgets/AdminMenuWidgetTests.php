<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminMenuWidget;

/**
 * Тестирует класс AdminMenuWidget
 */
class AdminMenuWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminMenuWidget::class);
        
        $this->assertTrue($reflection->hasProperty('options'));
    }
    
    /**
     * Тестирует метод AdminMenuWidget::setItems
     */
    public function testSetItemsNotOrders()
    {
        $widget = new AdminMenuWidget();
        
        $reflection = new \ReflectionMethod($widget, 'setItems');
        $reflection->setAccessible(true);
        $reflection->invoke($widget);
        
        $reflection = new \ReflectionProperty($widget, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertCount(9, $result);
    }
    
    /**
     * Тестирует метод AdminMenuWidget::run
     */
    public function testRun()
    {
        $widget = new AdminMenuWidget();
        $widget->run();
    }
}
