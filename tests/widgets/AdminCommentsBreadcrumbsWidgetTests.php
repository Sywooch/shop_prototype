<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminCommentsBreadcrumbsWidget;

/**
 * Тестирует класс AdminCommentsBreadcrumbsWidget
 */
class AdminCommentsBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminCommentsBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminCommentsBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

