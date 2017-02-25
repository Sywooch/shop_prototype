<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountChangeDataBreadcrumbsWidget;

/**
 * Тестирует класс AccountChangeDataBreadcrumbsWidget
 */
class AccountChangeDataBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AccountChangeDataBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AccountChangeDataBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

