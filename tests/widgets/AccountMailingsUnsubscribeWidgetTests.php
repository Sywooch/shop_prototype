<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountMailingsUnsubscribeWidget;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AccountMailingsUnsubscribeWidget
 */
class AccountMailingsUnsubscribeWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AccountMailingsUnsubscribeWidget();
    }
    
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
     */
    public function testSetMailings()
    {
        $mailings = [new class() {}];
        
        $this->widget->setMailings($mailings);
        
        $reflection = new \ReflectionProperty($this->widget, 'mailings');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends AbstractBaseForm {};
        
        $this->widget->setForm($form);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::setHeader
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
     * Тестирует метод AccountMailingsUnsubscribeWidget::setTemplate
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
     * Тестирует метод AccountMailingsUnsubscribeWidget::run
     * если пуст AccountMailingsUnsubscribeWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $this->widget->run();
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
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
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
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AccountMailingsUnsubscribeWidget::run
     * если подписки на отсутствуют
     */
    public function testRunWithoutMailings()
    {
        $mailings = [];
        
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mailings);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'account-mailings-unsubscribe.twig');
        
        $result = $this->widget->run();
        
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
        
        $form = new class() extends AbstractBaseForm {
            public $id;
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
        $reflection->setValue($this->widget, 'account-mailings-unsubscribe.twig');
        
        $result = $this->widget->run();
        
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
