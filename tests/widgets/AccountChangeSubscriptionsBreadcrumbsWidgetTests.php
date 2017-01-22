<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountChangeSubscriptionsBreadcrumbsWidget;

/**
 * Тестирует класс AccountChangeSubscriptionsBreadcrumbsWidget
 */
class AccountChangeSubscriptionsBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AccountChangeSubscriptionsBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AccountChangeSubscriptionsBreadcrumbsWidget();
        $widget->run();
    }
}

