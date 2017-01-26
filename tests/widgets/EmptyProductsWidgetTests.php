<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\EmptyProductsWidget;

/**
 * Тестирует класс EmptyProductsWidget
 */
class EmptyProductsWidgetTests extends TestCase
{
    /**
     * Тестирует свойства EmptyProductsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmptyProductsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод EmptyProductsWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new EmptyProductsWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод EmptyProductsWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new EmptyProductsWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод EmptyProductsWidget::run
     * если пуст EmptyProductsWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $widget = new EmptyProductsWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод EmptyProductsWidget::run
     */
    public function testRun()
    {
        $widget = new EmptyProductsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'empty-products.twig');
        
        $result = $widget->run();
        
        $this->assertEquals('<p>Поиск по этим параметрам не дал результатов</p>', trim($result));
    }
}
