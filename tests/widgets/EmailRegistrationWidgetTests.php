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
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод EmailRegistrationWidget::run
     * если пуст EmailRegistrationWidget::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: email
     */
    public function testRunEmailEmpty()
    {
        $widget = new EmailRegistrationWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод EmailRegistrationWidget::run
     * если пуст EmailRegistrationWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunViewEmpty()
    {
        $widget = new EmailRegistrationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'email');
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
        $reflection->setValue($widget, 'email@email.com');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setValue($widget, 'registration-mail.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<h1>Привет! Это информация о вашем аккаунте!</h1>#', $result);
        $this->assertRegExp('#<p>Вы можете управлять им в своем <a href="../vendor/phpunit/phpunit/login">личном кабинете</a>#', $result);
        $this->assertRegExp('#<br>Ваш логин: email@email.com#', $result);
    }
}
