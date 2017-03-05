<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminMailingsBreadcrumbsWidget;

/**
 * Тестирует класс AdminMailingsBreadcrumbsWidget
 */
class AdminMailingsBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminMailingsBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminMailingsBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

