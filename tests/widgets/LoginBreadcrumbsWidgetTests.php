<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\LoginBreadcrumbsWidget;

/**
 * Тестирует класс LoginBreadcrumbsWidget
 */
class LoginBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод LoginBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new LoginBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

