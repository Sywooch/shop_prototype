<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\MailingsFormWidget;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс MailingsFormWidget
 */
class MailingsFormWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new MailingsFormWidget();
    }
    
    /**
     * Тестирует свойства MailingsFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingsFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('mailings'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод MailingsFormWidget::setMailings
     */
    public function testSetMailings()
    {
        $mock = [new class() {}];
        
        $this->widget->setMailings($mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'mailings');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод MailingsFormWidget::setForm
     */
    public function testSetForm()
    {
        $mock = new class() extends AbstractBaseForm {};
        
        $this->widget->setForm($mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод MailingsFormWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $this->widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод MailingsFormWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $this->widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод MailingsFormWidget::run
     * если пуст MailingsFormWidget::mailings
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: mailings
     */
    public function testRunEmptyMailings()
    {
        $this->widget->run();
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
        
        $reflection = new \ReflectionProperty($this->widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод MailingsFormWidget::run
     * если пуст MailingsFormWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод MailingsFormWidget::run
     * если пуст MailingsFormWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $this->widget->run();
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
        
        $form = new class() extends AbstractBaseForm {
            public $id;
            public $email;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mailings);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'mailings-form.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="mailings-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="text"\s.+\sname=".+\[email\]">#', $result);
        $this->assertRegExp('#<input type="checkbox" name=".+\[id\]\[\]" value="1">\sOne</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[id\]\[\]" value="2">\sTwo</label>#', $result);
        $this->assertRegExp('#<input type="submit" value="Подписаться">#', $result);
        $this->assertRegExp('#<div class="mailings-success"></div>#', $result);
        $this->assertRegExp('#<div class="mailings-form">#', $result);
    }
}
