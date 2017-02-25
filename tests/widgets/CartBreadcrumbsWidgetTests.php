<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CartBreadcrumbsWidget;

/**
 * Тестирует класс CartBreadcrumbsWidget
 */
class CartBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод CartBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new CartBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

