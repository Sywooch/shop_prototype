<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminSizesBreadcrumbsWidget;

/**
 * Тестирует класс AdminSizesBreadcrumbsWidget
 */
class AdminSizesBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminSizesBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminSizesBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

