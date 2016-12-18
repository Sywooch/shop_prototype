<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\SearchBreadcrumbsWidget;

/**
 * Тестирует класс SearchBreadcrumbsWidget
 */
class SearchBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SearchBreadcrumbsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('text'));
    }
    /**
     * Тестирует метод SearchBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new SearchBreadcrumbsWidget();
        $widget->run();
    }
}

