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
     * Тестирует метод SearchBreadcrumbsWidget::setText
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTextError()
    {
        $text = null;
        
        $widget = new SearchBreadcrumbsWidget();
        $widget->setText($text);
    }
    
    /**
     * Тестирует метод SearchBreadcrumbsWidget::setText
     */
    public function testSetText()
    {
        $text = 'Text';
        
        $widget = new SearchBreadcrumbsWidget();
        $widget->setText($text);
        
        $reflection = new \ReflectionProperty($widget, 'text');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод SearchBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new SearchBreadcrumbsWidget();
        $widget->run();
        
        $this->assertTrue(true);
    }
}

