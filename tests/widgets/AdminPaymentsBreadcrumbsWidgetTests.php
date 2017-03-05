<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminPaymentsBreadcrumbsWidget;

/**
 * Тестирует класс AdminPaymentsBreadcrumbsWidget
 */
class AdminPaymentsBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminPaymentsBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminPaymentsBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

