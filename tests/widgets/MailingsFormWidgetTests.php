<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\MailingsFormWidget;
use app\forms\MailingForm;

/**
 * Тестирует класс MailingsFormWidget
 */
class MailingsFormWidgetTests extends TestCase
{
    /**
     * Тестирует свойства MailingsFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingsFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('mailings'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод MailingsFormWidget::setMailings
     * если передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetMailingsError()
    {
        $mock = new class() {};
        
        $widget = new MailingsFormWidget();
        $widget->setMailings($mock);
    }
    
    /**
     * Тестирует метод MailingsFormWidget::setMailings
     */
    public function testSetMailings()
    {
        $mock = [new class() {}];
        
        $widget = new MailingsFormWidget();
        $widget->setMailings($mock);
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод MailingsFormWidget::setForm
     * если передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $mock = new class() {};
        
        $widget = new MailingsFormWidget();
        $widget->setForm($mock);
    }
    
    /**
     * Тестирует метод MailingsFormWidget::setForm
     */
    public function testSetForm()
    {
        $mock = new class() extends MailingForm {};
        
        $widget = new MailingsFormWidget();
        $widget->setForm($mock);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(MailingForm::class, $result);
    }
    
    /**
     * Тестирует метод MailingsFormWidget::run
     * если пуст MailingsFormWidget::mailings
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: mailings
     */
    public function testRunEmptyMailings()
    {
        $widget = new MailingsFormWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод MailingsFormWidget::run
     * если пуст MailingsFormWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $widget = new MailingsFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод MailingsFormWidget::run
     * если пуст MailingsFormWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $widget = new MailingsFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод MailingsFormWidget::run
     */
    public function testRun()
    {
        $mailings = [
            new class() {
                public $id = 1;
                public $name = 'One';
                public $description = 'One description';
            },
            new class() {
                public $id = 2;
                public $name = 'Two';
                public $description = 'Two description';
            },
        ];
        
        $form = new class() extends MailingForm {};
        
        $widget = new MailingsFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'mailings-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Подпишитесь сейчас!</strong></p>#', $result);
        $this->assertRegExp('#<form id="mailings-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="text"\s.+\sname=".+\[email\]">#', $result);
        $this->assertRegExp('#<input type="checkbox" name=".+\[id\]\[\]" value="1">\sOne</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[id\]\[\]" value="2">\sTwo</label>#', $result);
        $this->assertRegExp('#<input type="submit" value="Подписаться">#', $result);
        $this->assertRegExp('#<div class="mailings-success"></div>#', $result);
        $this->assertRegExp('#<div class="mailings-form">#', $result);
    }
}
