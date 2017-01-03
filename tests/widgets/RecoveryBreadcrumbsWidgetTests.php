<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\RecoveryBreadcrumbsWidget;

/**
 * Тестирует класс RecoveryBreadcrumbsWidget
 */
class RecoveryBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод RecoveryBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new RecoveryBreadcrumbsWidget();
        $widget->run();
    }
}

