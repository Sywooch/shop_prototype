<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminOrdersBreadcrumbsWidget;

/**
 * Тестирует класс AdminOrdersBreadcrumbsWidget
 */
class AdminOrdersBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminOrdersBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminOrdersBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

