<?php

namespace app\tests\widgtes;

use PHPUnit\Framework\TestCase;
use app\widgets\UserRecoverySuccessWidget;

/**
 * Тестирует класс UserRecoverySuccessWidget
 */
class UserRecoverySuccessWidgetTests extends TestCase
{
    /**
     * Тестирует свойства UserRecoverySuccessWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserRecoverySuccessWidget::class);
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод UserRecoverySuccessWidget::setEmail
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetEmailError()
    {
        $email = null;
        
        $widget = new UserRecoverySuccessWidget();
        $widget->setEmail($email);
    }
    
    /**
     * Тестирует метод UserRecoverySuccessWidget::setEmail
     */
    public function testSetEmail()
    {
        $email = 'email';
        
        $widget = new UserRecoverySuccessWidget();
        $widget->setEmail($email);
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод UserRecoverySuccessWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new UserRecoverySuccessWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод UserRecoverySuccessWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new UserRecoverySuccessWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод UserRecoverySuccessWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new UserRecoverySuccessWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод UserRecoverySuccessWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new UserRecoverySuccessWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод UserRecoverySuccessWidget::run
     * если пуст UserRecoverySuccessWidget::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testRunEmptyEmail()
    {
        $widget = new UserRecoverySuccessWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод UserRecoverySuccessWidget::run
     * если пуст UserRecoverySuccessWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $widget = new UserRecoverySuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'email@mail.com');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод UserRecoverySuccessWidget::run
     * если пуст UserRecoverySuccessWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new UserRecoverySuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'email@mail.com');
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод UserRecoverySuccessWidget::run
     */
    public function testRun()
    {
        $widget = new UserRecoverySuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'email@mail.com');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'recovery-success.twig');
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p>Инструкции для восстановления пароля отправлены на email@mail.com</p>#', $result);
    }
}
