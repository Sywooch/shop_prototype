<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountChangePasswordBreadcrumbsWidget;

/**
 * Тестирует класс AccountChangePasswordBreadcrumbsWidget
 */
class AccountChangePasswordBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AccountChangePasswordBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AccountChangePasswordBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

