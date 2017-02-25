<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountBreadcrumbsWidget;

/**
 * Тестирует класс AccountBreadcrumbsWidget
 */
class AccountBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AccountBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AccountBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

