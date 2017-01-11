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
     * Тестирует метод EmptySphinxWidget::run
     * при отсутствии EmptySphinxWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
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
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'empty-sphinx.twig');
        
        $result = $widget->run();
        
        $expectedText = \Yii::t('base', 'Search returned no results');
        
        $this->assertRegExp('#' . $expectedText . '#', $result);
    }
}
