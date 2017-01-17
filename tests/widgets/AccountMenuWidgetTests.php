<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountMenuWidget;

/**
 * Тестирует класс AccountMenuWidget
 */
class AccountMenuWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountMenuWidget::class);
        
        $this->assertTrue($reflection->hasProperty('options'));
    }
    
    /**
     * Тестирует метод AccountMenuWidget::setItems
     */
    public function testSetItems()
    {
        $widget = new AccountMenuWidget();
        
        $reflection = new \ReflectionMethod($widget, 'setItems');
        $reflection->setAccessible(true);
        $reflection->invoke($widget);
        
        $reflection = new \ReflectionProperty($widget, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertCount(6, $result);
    }
    
    /**
     * Тестирует метод AccountMenuWidget::run
     */
    public function testRun()
    {
        $widget = new AccountMenuWidget();
        $widget->run();
    }
}
