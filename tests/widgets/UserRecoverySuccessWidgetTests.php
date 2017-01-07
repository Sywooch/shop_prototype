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
        $this->assertTrue($reflection->hasProperty('view'));
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
     * если пуст UserRecoverySuccessWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new UserRecoverySuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setValue($widget, 'email@mail.com');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод UserRecoverySuccessWidget::run
     */
    public function testRun()
    {
        $widget = new UserRecoverySuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setValue($widget, 'email@mail.com');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setValue($widget, 'recovery-success.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Восстановление пароля</strong></p>#', $result);
        $this->assertRegExp('#<p>Инструкции для восстановления пароля отправлены на email@mail.com</p>#', $result);
    }
}
