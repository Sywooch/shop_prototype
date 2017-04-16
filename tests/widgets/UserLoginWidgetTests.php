<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UserLoginWidget;
use app\forms\UserLoginForm;

/**
 * Тестирует класс UserLoginWidget
 */
class UserLoginWidgetTests extends TestCase
{
    /**
     * Тестирует свойства UserLoginWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserLoginWidget::class);
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод UserLoginWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new UserLoginWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод UserLoginWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends UserLoginForm {};
        
        $widget = new UserLoginWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(UserLoginForm::class, $result);
    }
    
    /**
     * Тестирует метод UserLoginWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new UserLoginWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод UserLoginWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new UserLoginWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод UserLoginWidget::run
     * если пуст UserLoginWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $widget = new UserLoginWidget();
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод UserLoginWidget::run
     * если пуст UserLoginWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $form = new class() extends UserLoginForm {};
        
        $widget = new UserLoginWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод UserLoginWidget::run
     * если добавлены комментарии
     */
    public function testRun()
    {
        $form = new class() extends UserLoginForm {};
        
        $widget = new UserLoginWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'login-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<div id="login-form">#', $result);
        $this->assertRegExp('#<h1>Войти</h1>#', $result);
        $this->assertRegExp('#<form id="login-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[email\]" placeholder="Email">#', $result);
        $this->assertRegExp('#<input type="password" id=".+" class="form-control" name=".+\[password\]" placeholder="Пароль">#', $result);
        $this->assertRegExp('#<input type="submit" value="Отправить">#', $result);
        $this->assertRegExp('#<div class="recovery"><a href=".+" class="gray">Забыли пароль\?</a></div>#', $result);
    }
}
