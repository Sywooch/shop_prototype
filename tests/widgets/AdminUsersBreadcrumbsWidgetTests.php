<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminUsersBreadcrumbsWidget;

/**
 * Тестирует класс AdminUsersBreadcrumbsWidget
 */
class AdminUsersBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminUsersBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminUsersBreadcrumbsWidget();
        $widget->run();
    }
}

