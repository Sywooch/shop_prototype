<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminColorsBreadcrumbsWidget;

/**
 * Тестирует класс AdminColorsBreadcrumbsWidget
 */
class AdminColorsBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminColorsBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminColorsBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

