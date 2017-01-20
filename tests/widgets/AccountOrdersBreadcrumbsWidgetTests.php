<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountOrdersBreadcrumbsWidget;

/**
 * Тестирует класс AccountOrdersBreadcrumbsWidget
 */
class AccountOrdersBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AccountOrdersBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AccountOrdersBreadcrumbsWidget();
        $widget->run();
    }
}

