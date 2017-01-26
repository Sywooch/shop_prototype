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
     * Тестирует свойства EmptySphinxWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmptySphinxWidget::class);
        
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод EmptySphinxWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new EmptySphinxWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод EmptySphinxWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new EmptySphinxWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод EmptySphinxWidget::run
     * при отсутствии EmptySphinxWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new EmptySphinxWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод EmptySphinxWidget::run
     */
    public function testRun()
    {
        $widget = new EmptySphinxWidget();
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'empty-sphinx.twig');
        
        $result = $widget->run();
        
        $expectedText = \Yii::t('base', 'Search returned no results');
        
        $this->assertRegExp('#' . $expectedText . '#', $result);
    }
}
