<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminCategoriesBreadcrumbsWidget;

/**
 * Тестирует класс AdminCategoriesBreadcrumbsWidget
 */
class AdminCategoriesBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminCategoriesBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminCategoriesBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

