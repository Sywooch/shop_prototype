<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountChangePasswordSuccessWidget;

/**
 * Тестирует класс AccountChangePasswordSuccessWidget
 */
class AccountChangePasswordSuccessWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AccountChangePasswordSuccessWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountChangePasswordSuccessWidget::class);
        
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AccountChangePasswordSuccessWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AccountChangePasswordSuccessWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AccountChangePasswordSuccessWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AccountChangePasswordSuccessWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AccountChangePasswordSuccessWidget::run
     * если пуст AccountChangePasswordSuccessWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new AccountChangePasswordSuccessWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountChangePasswordSuccessWidget::run
     */
    public function testRun()
    {
        $widget = new AccountChangePasswordSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'paragraph.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p>Пароль успешно обновлен!</p>#', $result);
    }
}
