<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\MailingsWidget;
use app\forms\MailingForm;

/**
 * Тестирует класс MailingsWidget
 */
class MailingsWidgetTests extends TestCase
{
    /**
     * Тестирует свойства MailingsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('mailings'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод MailingsWidget::setMailings
     * если передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetMailingsError()
    {
        $mock = new class() {};
        
        $widget = new MailingsWidget();
        $widget->setMailings($mock);
    }
    
    /**
     * Тестирует метод MailingsWidget::setMailings
     */
    public function testSetMailings()
    {
        $mock = [new class() {}];
        
        $widget = new MailingsWidget();
        $widget->setMailings($mock);
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод MailingsWidget::setForm
     * если передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $mock = new class() {};
        
        $widget = new MailingsWidget();
        $widget->setForm($mock);
    }
    
    /**
     * Тестирует метод MailingsWidget::setForm
     */
    public function testSetForm()
    {
        $mock = new class() extends MailingForm {};
        
        $widget = new MailingsWidget();
        $widget->setForm($mock);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(MailingForm::class, $result);
    }
    
    /**
     * Тестирует метод MailingsWidget::run
     * если пуст MailingsWidget::mailings
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: mailings
     */
    public function testRunEmptyMailings()
    {
        $widget = new MailingsWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод MailingsWidget::run
     * если пуст MailingsWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $widget = new MailingsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод MailingsWidget::run
     * если пуст MailingsWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $widget = new MailingsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод MailingsWidget::run
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
        
        $widget = new MailingsWidget();
        
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
        
        print_r($result);
    }
}
