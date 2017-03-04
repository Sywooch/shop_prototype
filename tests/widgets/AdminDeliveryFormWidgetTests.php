<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\widgets\AdminDeliveryFormWidget;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminDeliveryFormWidget
 */
class AdminDeliveryFormWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AdminDeliveryFormWidget();
    }
    
    /**
     * Тестирует свойства AdminDeliveryFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminDeliveryFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('delivery'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminDeliveryFormWidget::setDelivery
     */
    public function testSetDelivery()
    {
        $delivery = new class() extends Model {};
        
        $this->widget->setDelivery($delivery);
        
        $reflection = new \ReflectionProperty($this->widget, 'delivery');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод AdminDeliveryFormWidget::setForm
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
     * Тестирует метод AdminDeliveryFormWidget::setTemplate
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
     * Тестирует метод AdminDeliveryFormWidget::run
     * если пуст AdminDeliveryFormWidget::delivery
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: delivery
     */
    public function testRunEmptyDelivery()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminDeliveryFormWidget::run
     * если пуст AdminDeliveryFormWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'delivery');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminDeliveryFormWidget::run
     * если пуст AdminDeliveryFormWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'delivery');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminDeliveryFormWidget::run
     */
    public function testRun()
    {
        $delivery = new class() {
            public $id = 1;
            public $name = 'Name 1';
            public $description = 'Description 1';
            public $price = 108.78;
            public $active = 1;
        };
        
        $form = new class() extends AbstractBaseForm {
            public $id;
            public $name;
            public $description;
            public $price;
            public $active;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'delivery');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $delivery);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-delivery-form.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<div class="admin-delivery-edit-form">#', $result);
        $this->assertRegExp('#<form id="admin-delivery-edit-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="1">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[name\]" value="Name [0-9]{1}">#', $result);
        $this->assertRegExp('#<textarea id=".+" class="form-control" name=".+\[description\]" rows="[0-9]{1,2}" cols="[0-9]{1,2}">Description [0-9]{1}</textarea>#', $result);
        $this->assertRegExp('#<input type="number" id=".+" class="form-control" name=".+\[price\]" value=".+" step="0.01" min="1">#', $result);
        $this->assertRegExp('#<label><input type="checkbox" id=".+" name=".+\[active\]" value="1" checked> Active</label>#', $result);
        $this->assertRegExp('#<input type="submit" name="send" value="Сохранить">#', $result);
        $this->assertRegExp('#<input type="submit" name="cancel" value="Отменить">#', $result);
    }
}
