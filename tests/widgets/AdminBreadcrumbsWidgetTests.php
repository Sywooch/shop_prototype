<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminBreadcrumbsWidget;

/**
 * Тестирует класс AdminBreadcrumbsWidget
 */
class AdminBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminBreadcrumbsWidget();
        $widget->run();
    }
}

