<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\RegistrationBreadcrumbsWidget;

/**
 * Тестирует класс RegistrationBreadcrumbsWidget
 */
class RegistrationBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод RegistrationBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new RegistrationBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

