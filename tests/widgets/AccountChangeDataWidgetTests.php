<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountChangeDataWidget;
use app\forms\UserUpdateForm;

/**
 * Тестирует класс AccountChangeDataWidget
 */
class AccountChangeDataWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AccountChangeDataWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountChangeDataWidget::class);
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AccountChangeDataWidget::setForm
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new AccountChangeDataWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод AccountChangeDataWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends UserUpdateForm {};
        
        $widget = new AccountChangeDataWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(UserUpdateForm::class, $result);
    }
    
    /**
     * Тестирует метод AccountChangeDataWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new AccountChangeDataWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод AccountChangeDataWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new AccountChangeDataWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AccountChangeDataWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AccountChangeDataWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AccountChangeDataWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AccountChangeDataWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AccountChangeDataWidget::run
     * если пуст AccountChangeDataWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $widget = new AccountChangeDataWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountChangeDataWidget::run
     * если пуст AccountChangeDataWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $widget = new AccountChangeDataWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountChangeDataWidget::run
     * если пуст AccountChangeDataWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $widget = new AccountChangeDataWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountChangeDataWidget::run
     */
    public function testRun()
    {
        $form = new class() extends UserUpdateForm {};
        
        $widget = new AccountChangeDataWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-change-data-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="change-data-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[name\]">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[surname\]">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[phone\]">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[address\]">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[city\]">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[country\]">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[postcode\]">#', $result);
        $this->assertRegExp('#<input type="submit" value="Изменить">#', $result);
    }
    
    /**
     * Тестирует метод AccountChangeDataWidget::run
     * если форма заполнена предварительными данными
     */
    public function testRunNotEmptyForm()
    {
        $form = new class() extends UserUpdateForm {};
        $form->name = 'John';
        $form->surname = 'Doe';
        $form->phone = '+897 568-89-78';
        $form->address = 'Hannower str. 33';
        $form->city = 'New York';
        $form->country = 'USA';
        $form->postcode = '23654';
        
        $widget = new AccountChangeDataWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-change-data-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="change-data-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[name\]" value="John">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[surname\]" value="Doe">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[phone\]" value="\+897 568-89-78">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[address\]" value="Hannower str. 33">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[city\]" value="New York">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[country\]" value="USA">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[postcode\]" value="23654">#', $result);
        $this->assertRegExp('#<input type="submit" value="Изменить">#', $result);
    }
}
