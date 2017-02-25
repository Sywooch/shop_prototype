<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminChangeUserDataWidget;
use app\forms\UserUpdateForm;

/**
 * Тестирует класс AdminChangeUserDataWidget
 */
class AdminChangeUserDataWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AdminChangeUserDataWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminChangeUserDataWidget::class);
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminChangeUserDataWidget::setForm
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new AdminChangeUserDataWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод AdminChangeUserDataWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends UserUpdateForm {};
        
        $widget = new AdminChangeUserDataWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(UserUpdateForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminChangeUserDataWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new AdminChangeUserDataWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод AdminChangeUserDataWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new AdminChangeUserDataWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminChangeUserDataWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AdminChangeUserDataWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AdminChangeUserDataWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AdminChangeUserDataWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminChangeUserDataWidget::run
     * если пуст AdminChangeUserDataWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $widget = new AdminChangeUserDataWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminChangeUserDataWidget::run
     * если пуст AdminChangeUserDataWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $widget = new AdminChangeUserDataWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminChangeUserDataWidget::run
     * если пуст AdminChangeUserDataWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $widget = new AdminChangeUserDataWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminChangeUserDataWidget::run
     */
    public function testRun()
    {
        $form = new class() extends UserUpdateForm {};
        
        $widget = new AdminChangeUserDataWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'admin-change-user-data-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="admin-user-data-change-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]">#', $result);
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
     * Тестирует метод AdminChangeUserDataWidget::run
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
        
        $widget = new AdminChangeUserDataWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'admin-change-user-data-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="admin-user-data-change-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]">#', $result);
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
