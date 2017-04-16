<?php

namespace app\tests\widgtes;

use PHPUnit\Framework\TestCase;
use app\widgets\UserRegistrationSuccessWidget;

/**
 * Тестирует класс UserRegistrationSuccessWidget
 */
class UserRegistrationSuccessWidgetTests extends TestCase
{
    /**
     * Тестирует свойства UserRegistrationSuccessWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserRegistrationSuccessWidget::class);
        
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод UserRegistrationSuccessWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new UserRegistrationSuccessWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод UserRegistrationSuccessWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new UserRegistrationSuccessWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод UserRegistrationSuccessWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new UserRegistrationSuccessWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод UserRegistrationSuccessWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new UserRegistrationSuccessWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод UserRegistrationSuccessWidget::run
     * если пуст UserRegistrationSuccessWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $widget = new UserRegistrationSuccessWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод UserRegistrationSuccessWidget::run
     * если пуст UserRegistrationSuccessWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new UserRegistrationSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод UserRegistrationSuccessWidget::run
     */
    public function testRun()
    {
        $widget = new UserRegistrationSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'registration-success.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p>Вы успешно зарегистрировались! Теперь вы можете войти в систему с помощью логина и пароля</p>#', $result);
        $this->assertRegExp('#<p><a href=".+">Войти</a></p>#', $result);
    }
}
