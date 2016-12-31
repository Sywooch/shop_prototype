<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UserRegistrationWidget;
use app\forms\UserRegistrationForm;

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
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод UserRegistrationWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new UserRegistrationWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод UserRegistrationWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends UserRegistrationForm {};
        
        $widget = new UserRegistrationWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(UserRegistrationForm::class, $result);
    }
    
    /**
     * Тестирует метод UserRegistrationWidget::run
     * если пуст UserRegistrationWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: form
     */
    public function testRunEmptyForm()
    {
        $widget = new UserRegistrationWidget();
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод UserRegistrationWidget::run
     * если пуст UserRegistrationWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $form = new class() extends UserRegistrationForm {};
        
        $widget = new UserRegistrationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод UserRegistrationWidget::run
     * если добавлены комментарии
     */
    public function testRun()
    {
        $form = new class() extends UserRegistrationForm {};
        
        $widget = new UserRegistrationWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'registration-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Регистрация</strong></p>#', $result);
        $this->assertRegExp('#<form id="registration-form" action="#', $result);
        $this->assertRegExp('#<label.+>Email</label>#', $result);
        $this->assertRegExp('#<input type="text"#', $result);
        $this->assertRegExp('#<label.+>Password</label>#', $result);
        $this->assertRegExp('#<input type="password"#', $result);
        $this->assertRegExp('#<label.+>Password2</label>#', $result);
        $this->assertRegExp('#<input type="submit" value="Отправить">#', $result);
    }
}
