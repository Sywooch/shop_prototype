<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UnsubscribeEmptyWidget;

/**
 * Тестирует класс UnsubscribeEmptyWidget
 */
class UnsubscribeEmptyWidgetTests extends TestCase
{
    /**
     * Тестирует свойства UnsubscribeEmptyWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UnsubscribeEmptyWidget::class);
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод UnsubscribeEmptyWidget::setEmail
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetEmailError()
    {
        $email = null;
        
        $widget = new UnsubscribeEmptyWidget();
        $widget->setEmail($email);
    }
    
    /**
     * Тестирует метод UnsubscribeEmptyWidget::setEmail
     */
    public function testSetEmail()
    {
        $email = 'email';
        
        $widget = new UnsubscribeEmptyWidget();
        $widget->setEmail($email);
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод UnsubscribeEmptyWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new UnsubscribeEmptyWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод UnsubscribeEmptyWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new UnsubscribeEmptyWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод UnsubscribeEmptyWidget::run
     * если пуст UnsubscribeEmptyWidget::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testRunEmptyEmail()
    {
        $widget = new UnsubscribeEmptyWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод UnsubscribeEmptyWidget::run
     * если пуст UnsubscribeEmptyWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $widget = new UnsubscribeEmptyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'some@some.com');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод UnsubscribeEmptyWidget::run
     */
    public function testRun()
    {
        $widget = new UnsubscribeEmptyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'some@some.com');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'empty.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<h1>Отписаться</h1>#', $result);
        $this->assertRegExp('#<p class="long-text">Email some@some.com не связан ни с одной рассылкой!</p>#', $result);
    }
}
