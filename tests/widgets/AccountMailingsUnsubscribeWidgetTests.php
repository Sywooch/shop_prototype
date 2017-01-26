<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountMailingsUnsubscribeWidget;
use app\forms\MailingForm;

/**
 * Тестирует класс AccountMailingsUnsubscribeWidget
 */
class AccountMailingsUnsubscribeWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AccountMailingsUnsubscribeWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountMailingsUnsubscribeWidget::class);
        
        $this->assertTrue($reflection->hasProperty('mailings'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::setMailings
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetMailingsError()
    {
        $mailings = new class() {};
        
        $widget = new AccountMailingsUnsubscribeWidget();
        $widget->setMailings($mailings);
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::setMailings
     */
    public function testSetMailings()
    {
        $mailings = [new class() {}];
        
        $widget = new AccountMailingsUnsubscribeWidget();
        $widget->setMailings($mailings);
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::setForm
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new AccountMailingsUnsubscribeWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends MailingForm {};
        
        $widget = new AccountMailingsUnsubscribeWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(MailingForm::class, $result);
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new AccountMailingsUnsubscribeWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new AccountMailingsUnsubscribeWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AccountMailingsUnsubscribeWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AccountMailingsUnsubscribeWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::run
     * если пуст AccountMailingsUnsubscribeWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $widget = new AccountMailingsUnsubscribeWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::run
     * если пуст AccountMailingsUnsubscribeWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $widget = new AccountMailingsUnsubscribeWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::run
     * если пуст AccountMailingsUnsubscribeWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $widget = new AccountMailingsUnsubscribeWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::run
     * если подписки на отсутствуют
     */
    public function testRunWithoutMailings()
    {
        $mailings = [];
        
        $mock = new class() {};
        
        $widget = new AccountMailingsUnsubscribeWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-mailings-unsubscribe.twig');
        
        $result = $widget->run();
        
        $this->assertEmpty(trim($result));
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::run
     * если есть подписки
     */
    public function testRun()
    {
        $mailings = [
            new class() {
                public $id = 1;
                public $name = 'Mailing 1';
                public $description = 'Mailing description 1';
            },
            new class() {
                public $id = 2;
                public $name = 'Mailing 2';
                public $description = 'Mailing description 2';
            },
        ];
        
        $form = new class() extends MailingForm {};
        
        $widget = new AccountMailingsUnsubscribeWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-mailings-unsubscribe.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#Mailing 1#', $result);
        $this->assertRegExp('#<br>Mailing description 1#', $result);
        $this->assertRegExp('#Mailing 2#', $result);
        $this->assertRegExp('#<br>Mailing description 2#', $result);
        $this->assertRegExp('#<form id="mailing-cancellation-form-\d{1,}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="\d{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Отменить">#', $result);
    }
}
