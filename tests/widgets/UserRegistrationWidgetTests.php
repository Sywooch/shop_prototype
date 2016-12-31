<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UserRegistrationWidget;

/**
 * Тестирует класс UserRegistrationWidget
 */
class UserRegistrationWidgetTests extends TestCase
{
    /**
     * Тестирует свойства UserRegistrationWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserRegistrationWidget::class);
        
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод UserRegistrationWidget::run
     * если пуст UserRegistrationWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $widget = new UserRegistrationWidget();
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод CommentsWidget::run
     * если добавлены комментарии
     */
    public function testRun()
    {
        $widget = new UserRegistrationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'registration-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Регистрация</strong></p>#', $result);
        $this->assertRegExp('#<form id="registration-form" action="#', $result);
        $this->assertRegExp('#<label.+>Email</label>#', $result);
        $this->assertRegExp('#<input type="text"#', $result);
        $this->assertRegExp('#<label.+>Password</label>#', $result);
        $this->assertRegExp('#<input type="password" id="userregistrationform-password"#', $result);
        $this->assertRegExp('#<label.+>Password2</label>#', $result);
        $this->assertRegExp('#<input type="password" id="userregistrationform-password2"#', $result);
        $this->assertRegExp('#<input type="submit" value="Отправить">#', $result);
    }
}
