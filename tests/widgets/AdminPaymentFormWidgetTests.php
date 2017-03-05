<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\widgets\AdminPaymentFormWidget;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminPaymentFormWidget
 */
class AdminPaymentFormWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AdminPaymentFormWidget();
    }
    
    /**
     * Тестирует свойства AdminPaymentFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminPaymentFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('payment'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminPaymentFormWidget::setPayment
     */
    public function testSetPayment()
    {
        $payment = new class() extends Model {};
        
        $this->widget->setPayment($payment);
        
        $reflection = new \ReflectionProperty($this->widget, 'payment');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод AdminPaymentFormWidget::setForm
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
     * Тестирует метод AdminPaymentFormWidget::setTemplate
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
     * Тестирует метод AdminPaymentFormWidget::run
     * если пуст AdminPaymentFormWidget::payment
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: payment
     */
    public function testRunEmptyPayment()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminPaymentFormWidget::run
     * если пуст AdminPaymentFormWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'payment');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminPaymentFormWidget::run
     * если пуст AdminPaymentFormWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'payment');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminPaymentFormWidget::run
     */
    public function testRun()
    {
        $payment = new class() {
            public $id = 1;
            public $name = 'Name 1';
            public $description = 'Description 1';
            public $active = 1;
        };
        
        $form = new class() extends AbstractBaseForm {
            public $id;
            public $name;
            public $description;
            public $active;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'payment');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $payment);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-payment-form.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<div class="admin-payment-edit-form">#', $result);
        $this->assertRegExp('#<form id="admin-payment-edit-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="1">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[name\]" value="Name [0-9]{1}">#', $result);
        $this->assertRegExp('#<textarea id=".+" class="form-control" name=".+\[description\]" rows="[0-9]{1,2}" cols="[0-9]{1,2}">Description [0-9]{1}</textarea>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" id=".+" name=".+\[active\]" value="1" checked> Active</label>#', $result);
        $this->assertRegExp('#<input type="submit" name="send" value="Сохранить">#', $result);
        $this->assertRegExp('#<input type="submit" name="cancel" value="Отменить">#', $result);
    }
}
