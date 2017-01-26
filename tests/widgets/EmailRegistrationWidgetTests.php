<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\EmailRegistrationWidget;
use app\controllers\UserController;

/**
 * Тестирует класс EmailRegistrationWidget
 */
class EmailRegistrationWidgetTests extends TestCase
{
    /**
     * Тестирует свойства EmailRegistrationWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmailRegistrationWidget::class);
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод EmailRegistrationWidget::setEmail
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetEmailError()
    {
        $email = null;
        
        $widget = new EmailRegistrationWidget();
        $widget->setEmail($email);
    }
    
    /**
     * Тестирует метод EmailRegistrationWidget::setEmail
     */
    public function testSetEmail()
    {
        $email = 'email';
        
        $widget = new EmailRegistrationWidget();
        $widget->setEmail($email);
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод EmailRegistrationWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new EmailRegistrationWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод EmailRegistrationWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new EmailRegistrationWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод EmailRegistrationWidget::run
     * если пуст EmailRegistrationWidget::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testRunEmailEmpty()
    {
        $widget = new EmailRegistrationWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод EmailRegistrationWidget::run
     * если пуст EmailRegistrationWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunViewEmpty()
    {
        $widget = new EmailRegistrationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'email@email.com');
        
        $widget->run();
    }
    
     /**
     * Тестирует метод EmailRegistrationWidget::run
     */
    public function testRun()
    {
        \Yii::$app->controller = new UserController('user', \Yii::$app);
        
        $widget = new EmailRegistrationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'email@email.com');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'registration-mail.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<h1>Привет! Это информация о вашем аккаунте!</h1>#', $result);
        $this->assertRegExp('#<p>Вы можете управлять им в своем <a href="../vendor/phpunit/phpunit/login">личном кабинете</a>#', $result);
        $this->assertRegExp('#<br>Ваш логин: email@email.com#', $result);
    }
}
