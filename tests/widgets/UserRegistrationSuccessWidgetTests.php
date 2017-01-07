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
        
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод UserRegistrationSuccessWidget::run
     * если пуст UserRegistrationSuccessWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new UserRegistrationSuccessWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод UserRegistrationSuccessWidget::run
     */
    public function testRun()
    {
        $widget = new UserRegistrationSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setValue($widget, 'registration-success.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p>Вы успешно зарегистрировались! Теперь вы можете войти в систему с помощью логина и пароля</p>#', $result);
    }
}
