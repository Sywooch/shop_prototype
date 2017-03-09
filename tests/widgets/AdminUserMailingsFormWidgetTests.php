<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminUserMailingsFormWidget;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminUserMailingsFormWidget
 */
class AdminUserMailingsFormWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AdminUserMailingsFormWidget();
    }
    
    /**
     * Тестирует свойства AdminUserMailingsFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminUserMailingsFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('mailings'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminUserMailingsFormWidget::setMailings
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
     * Тестирует метод AdminUserMailingsFormWidget::setForm
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
     * Тестирует метод AdminUserMailingsFormWidget::setHeader
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
     * Тестирует метод AdminUserMailingsFormWidget::setTemplate
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
     * Тестирует метод AdminUserMailingsFormWidget::run
     * если пуст AdminUserMailingsFormWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminUserMailingsFormWidget::run
     * если пуст AdminUserMailingsFormWidget::header
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
     * Тестирует метод AdminUserMailingsFormWidget::run
     * если пуст AdminUserMailingsFormWidget::template
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
     * Тестирует метод AdminUserMailingsFormWidget::run
     * если подписки на рассылки отсутствуют
     */
    public function testRunWithoutMailings()
    {
        $mailings = [];
        
        $form = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionProperty($this->widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mailings);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-mailings-form.twig');
        
        $result = $this->widget->run();
        
        $this->assertEmpty(trim($result));
    }
    
    /**
     * Тестирует метод AdminUserMailingsFormWidget::run
     * если есть подписки на рассылки
     */
    public function testRun()
    {
        $form = new class() extends AbstractBaseForm {
            public $id;
            public $id_user = 1;
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
        $reflection->setValue($this->widget, 'admin-mailings-form.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<li class="admin-user-mailings-form-\d">#', $result);
        $this->assertRegExp('#Mailing \d#', $result);
        $this->assertRegExp('#Mailing description \d#', $result);
        $this->assertRegExp('#<form id="admin-user-mailings-form-\d" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id_user\]" value="\d{1}">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="\d{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Подписаться">#', $result);
    }
}
