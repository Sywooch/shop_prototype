<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminDeliveriesBreadcrumbsWidget;

/**
 * Тестирует класс AdminDeliveriesBreadcrumbsWidget
 */
class AdminDeliveriesBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminDeliveriesBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminDeliveriesBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

