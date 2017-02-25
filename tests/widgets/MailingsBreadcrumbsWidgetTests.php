<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\MailingsBreadcrumbsWidget;

/**
 * Тестирует класс MailingsBreadcrumbsWidget
 */
class MailingsBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод MailingsBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new MailingsBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

