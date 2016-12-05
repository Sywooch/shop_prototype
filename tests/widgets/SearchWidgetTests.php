<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\SearchWidget;

/**
 * Тестирует класс SearchWidget
 */
class SearchWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SearchWidget::class);
        
        $this->assertTrue($reflection->hasProperty('text'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод SearchWidget::run
     * при отсутствии SearchWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $widget = new SearchWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод SearchWidget::run
     * при отсутствии SearchWidget::text
     */
    public function testRunWithoutText()
    {
        $widget = new SearchWidget();
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setValue($widget, 'search.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('/<div class="search-form">/', $result);
        $this->assertRegExp('/<form id="search-form" name="search-form"/', $result);
        $this->assertRegExp('/value=""/', $result);
        $this->assertRegExp('/<input type="submit" value="'. \Yii::t('base', 'Search') . '">/', $result);
    }
    
    /**
     * Тестирует метод SearchWidget::run
     */
    public function testRunWithText()
    {
        $widget = new SearchWidget();
        
        $reflection = new \ReflectionProperty($widget, 'text');
        $reflection->setValue($widget, 'Silver moon');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setValue($widget, 'search.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('/<div class="search-form">/', $result);
        $this->assertRegExp('/<form id="search-form" name="search-form"/', $result);
        $this->assertRegExp('/value="Silver moon"/', $result);
        $this->assertRegExp('/<input type="submit" value="'. \Yii::t('base', 'Search') . '">/', $result);
    }
}
