<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\PasswordGenerateWidget;
use app\forms\RecoveryPasswordForm;

/**
 * Тестирует класс PasswordGenerateWidget
 */
class PasswordGenerateWidgetTests extends TestCase
{
    /**
     * Тестирует свойства PasswordGenerateWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PasswordGenerateWidget::class);
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод PasswordGenerateWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new PasswordGenerateWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод PasswordGenerateWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends RecoveryPasswordForm {};
        
        $widget = new PasswordGenerateWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(RecoveryPasswordForm::class, $result);
    }
    
    /**
     * Тестирует метод PasswordGenerateWidget::run
     * если пуст PasswordGenerateWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: form
     */
    public function testRunEmptyForm()
    {
        $widget = new PasswordGenerateWidget();
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод PasswordGenerateWidget::run
     * если пуст PasswordGenerateWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $form = new class() extends RecoveryPasswordForm {};
        
        $widget = new PasswordGenerateWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод PasswordGenerateWidget::run
     * если добавлены комментарии
     */
    public function testRun()
    {
        $form = new class() extends RecoveryPasswordForm {};
        
        $widget = new PasswordGenerateWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'generate-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Восстановление пароля</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>Введите ваш email</strong></p>#', $result);
        $this->assertRegExp('#<form id="generate-password-form"#', $result);
        $this->assertRegExp('#<input type="text"#', $result);
        $this->assertRegExp('#<input type="submit" value="Отправить">#', $result);
    }
}
