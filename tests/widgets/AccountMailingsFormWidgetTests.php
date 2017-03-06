<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountMailingsFormWidget;
use app\forms\AbstractBaseForm;

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
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
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
        $form = new class() extends AbstractBaseForm {};
        
        $widget = new AccountMailingsFormWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new AccountMailingsFormWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new AccountMailingsFormWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AccountMailingsFormWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AccountMailingsFormWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
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
     * если пуст AccountMailingsFormWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $widget = new AccountMailingsFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::run
     * если пуст AccountMailingsFormWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $widget = new AccountMailingsFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountMailingsFormWidget::run
     * если подписки на рассылки отсутствуют
     */
    public function testRunWithoutMailings()
    {
        $mailings = [];
        
        $form = new class() extends AbstractBaseForm {};
        
        $widget = new AccountMailingsFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'template');
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
        $form = new class() extends AbstractBaseForm {
            public $id;
        };
        
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
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-mailings-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<li class="account-mailings-form-\d">#', $result);
        $this->assertRegExp('#Mailing \d#', $result);
        $this->assertRegExp('#Mailing description \d#', $result);
        $this->assertRegExp('#<form id="account-mailings-form-\d" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="\d{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Подписаться">#', $result);
    }
}
