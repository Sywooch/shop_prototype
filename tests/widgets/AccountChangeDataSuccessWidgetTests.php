<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountChangeDataSuccessWidget;

/**
 * Тестирует класс AccountChangeDataSuccessWidget
 */
class AccountChangeDataSuccessWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AccountChangeDataSuccessWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountChangeDataSuccessWidget::class);
        
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AccountChangeDataSuccessWidget::run
     * если пуст AccountChangeDataSuccessWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $widget = new AccountChangeDataSuccessWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountChangeDataSuccessWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AccountChangeDataSuccessWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AccountChangeDataSuccessWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AccountChangeDataSuccessWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AccountChangeDataSuccessWidget::run
     */
    public function testRun()
    {
        $widget = new AccountChangeDataSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'paragraph.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p>Данные успешно обновлены!</p>#', $result);
    }
}
