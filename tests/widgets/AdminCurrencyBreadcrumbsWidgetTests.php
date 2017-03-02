<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminCurrencyBreadcrumbsWidget;

/**
 * Тестирует класс AdminCurrencyBreadcrumbsWidget
 */
class AdminCurrencyBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminCurrencyBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminCurrencyBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

