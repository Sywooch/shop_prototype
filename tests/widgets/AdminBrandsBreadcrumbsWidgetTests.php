<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminBrandsBreadcrumbsWidget;

/**
 * Тестирует класс AdminBrandsBreadcrumbsWidget
 */
class AdminBrandsBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminBrandsBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminBrandsBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

