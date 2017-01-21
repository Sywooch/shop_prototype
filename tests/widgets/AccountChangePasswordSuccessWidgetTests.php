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
        
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод AccountChangePasswordSuccessWidget::run
     * если пуст AccountChangePasswordSuccessWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
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
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-change-password-success.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p>Пароль успешно обновлен!</p>#', $result);
    }
}
