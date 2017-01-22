<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountMailingsFormWidget;
use app\forms\MailingForm;

/**
 * Тестирует класс AccountMailingsFormWidget
 */
class AccountMailingsFormWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AccountMailingsFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountMailingsFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('mailings'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::setMailings
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetMailingsError()
    {
        $mailings = new class() {};
        
        $widget = new AccountMailingsFormWidget();
        $widget->setMailings($mailings);
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::setMailings
     */
    public function testSetMailings()
    {
        $mailings = [new class() {}];
        
        $widget = new AccountMailingsFormWidget();
        $widget->setMailings($mailings);
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::setForm
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new AccountMailingsFormWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends MailingForm {};
        
        $widget = new AccountMailingsFormWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(MailingForm::class, $result);
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::run
     * если пуст AccountMailingsFormWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $widget = new AccountMailingsFormWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::run
     * если пуст AccountMailingsFormWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $form = new class() extends MailingForm {};
        
        $widget = new AccountMailingsFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::run
     * если подписки на рассылки отсутствуют
     */
    public function testRunWithoutMailings()
    {
        $mailings = [];
        
        $form = new class() extends MailingForm {};
        
        $widget = new AccountMailingsFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-mailings-form.twig');
        
        $result = $widget->run();
        
        $this->assertEmpty(trim($result));
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::run
     * если есть подписки на рассылки
     */
    public function testRun()
    {
        $form = new class() extends MailingForm {};
        
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
        
        $widget = new AccountMailingsFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-mailings-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<div class="account-subscribe">#', $result);
        $this->assertRegExp('#<p><strong>Подпишитесь сейчас!</strong></p>#', $result);
        $this->assertRegExp('#<li class="account-mailings-form-\d">#', $result);
        $this->assertRegExp('#Mailing \d#', $result);
        $this->assertRegExp('#Mailing description \d#', $result);
        $this->assertRegExp('#<form id="account-mailings-form-\d" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]">#', $result);
        $this->assertRegExp('#<input type="submit" value="Подписаться">#', $result);
    }
}
