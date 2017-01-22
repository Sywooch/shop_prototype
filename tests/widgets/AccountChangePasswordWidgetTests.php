<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountChangePasswordWidget;
use app\forms\UserChangePasswordForm;

/**
 * Тестирует класс AccountChangePasswordWidget
 */
class AccountChangePasswordWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AccountChangePasswordWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountChangePasswordWidget::class);
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод AccountChangePasswordWidget::setForm
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new AccountChangePasswordWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод AccountChangePasswordWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends UserChangePasswordForm {};
        
        $widget = new AccountChangePasswordWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(UserChangePasswordForm::class, $result);
    }
    
    /**
     * Тестирует метод AccountChangePasswordWidget::run
     * если пуст AccountChangePasswordWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $widget = new AccountChangePasswordWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountChangePasswordWidget::run
     * если пуст AccountChangePasswordWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $widget = new AccountChangePasswordWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountChangePasswordWidget::run
     */
    public function testRun()
    {
        $form = new class() extends UserChangePasswordForm {};
        
        $widget = new AccountChangePasswordWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-change-password-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Изменить пароль</strong></p>#', $result);
        $this->assertRegExp('#<form id="change-password-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="password" id=".+" class="form-control" name=".+\[currentPassword\]">#', $result);
        $this->assertRegExp('#<input type="password" id=".+" class="form-control" name=".+\[password\]">#', $result);
        $this->assertRegExp('#<input type="password" id=".+" class="form-control" name=".+\[password2\]">#', $result);
        $this->assertRegExp('#<input type="submit" value="Изменить">#', $result);
    }
}
