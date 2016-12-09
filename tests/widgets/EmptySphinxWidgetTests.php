<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\EmptySphinxWidget;

/**
 * Тестирует класс EmptySphinxWidget
 */
class EmptySphinxWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmptySphinxWidget::class);
        
        $this->assertTrue($reflection->hasProperty('text'));
    }
    
    /**
     * Тестирует метод EmptySphinxWidget::run
     * при отсутствии EmptySphinxWidget::text
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: text
     */
    public function testRunEmptyText()
    {
        $widget = new EmptySphinxWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод EmptySphinxWidget::run
     * при отсутствии EmptySphinxWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $widget = new EmptySphinxWidget();
        
        $reflection = new \ReflectionProperty($widget, 'text');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'some text');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CartWidget::run
     */
    public function testRun()
    {
        $widget = new EmptySphinxWidget();
        
        $reflection = new \ReflectionProperty($widget, 'text');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'some text');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'empty-sphinx.twig');
        
        $result = $widget->run();
        
        $expectedText = \Yii::t('base', 'Search for <strong>{placeholder}</strong> returned no results', ['placeholder'=>'some text']);
        
        $this->assertRegExp('#' . $expectedText . '#', $result);
    }
}
