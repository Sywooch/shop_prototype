<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminProductsBreadcrumbsWidget;

/**
 * Тестирует класс AdminProductsBreadcrumbsWidget
 */
class AdminProductsBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminProductsBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminProductsBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

