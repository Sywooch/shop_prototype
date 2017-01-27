<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UserRecoveryWidget;
use app\forms\RecoveryPasswordForm;

/**
 * Тестирует класс UserRecoveryWidget
 */
class UserRecoveryWidgetTests extends TestCase
{
    /**
     * Тестирует свойства UserRecoveryWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserRecoveryWidget::class);
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод UserRecoveryWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new UserRecoveryWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод UserRecoveryWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends RecoveryPasswordForm {};
        
        $widget = new UserRecoveryWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(RecoveryPasswordForm::class, $result);
    }
    
    /**
     * Тестирует метод UserRecoveryWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new UserRecoveryWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод UserRecoveryWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new UserRecoveryWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод UserRecoveryWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new UserRecoveryWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод UserRecoveryWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new UserRecoveryWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод UserRecoveryWidget::run
     * если пуст UserRecoveryWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $widget = new UserRecoveryWidget();
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод UserRecoveryWidget::run
     * если пуст UserRecoveryWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $form = new class() extends RecoveryPasswordForm {};
        
        $widget = new UserRecoveryWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод UserRecoveryWidget::run
     * если пуст UserRecoveryWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $form = new class() extends RecoveryPasswordForm {};
        
        $widget = new UserRecoveryWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'Header');
        
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод UserRecoveryWidget::run
     * если добавлены комментарии
     */
    public function testRun()
    {
        $form = new class() extends RecoveryPasswordForm {};
        
        $widget = new UserRecoveryWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'recovery-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p>Чтобы продолжить восстановление пароля, введите ваш email</p>#', $result);
        $this->assertRegExp('#<form id="recovery-password-form"#', $result);
        $this->assertRegExp('#<input type="submit" value="Отправить">#', $result);
    }
}
