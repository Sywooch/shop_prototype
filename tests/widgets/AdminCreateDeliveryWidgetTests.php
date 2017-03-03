<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminCreateDeliveryWidget;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminCreateDeliveryWidget
 */
class AdminCreateDeliveryWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AdminCreateDeliveryWidget();
    }
    
    /**
     * Тестирует свойства AdminCreateDeliveryWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCreateDeliveryWidget::class);
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminCreateDeliveryWidget::setForm
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
     * Тестирует метод AdminCreateDeliveryWidget::setHeader
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
     * Тестирует метод AdminCreateDeliveryWidget::setTemplate
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
     * Тестирует метод AdminCreateDeliveryWidget::run
     * если пуст AdminCreateDeliveryWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $result = $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCreateDeliveryWidget::run
     * если пуст AdminCreateDeliveryWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $form = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $form);
        
        $result = $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCreateDeliveryWidget::run
     * если пуст AdminCreateDeliveryWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $form = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, 'Header');
        
        $result = $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCreateDeliveryWidget::run
     */
    public function testRun()
    {
        $form = new class() extends AbstractBaseForm {
            public $name;
            public $description;
            public $price;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, 'admin-create-delivery.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="delivery-create-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[name\]">#', $result);
        $this->assertRegExp('#<textarea id=".+" class="form-control" name=".+\[description\]" rows="[0-9]{1,2}" cols="[0-9]{1,2}"></textarea>#', $result);
        $this->assertRegExp('#<input type="number" id=".+" class="form-control" name=".+\[price\]" step="0.01" min="1">#', $result);
        $this->assertRegExp('#<input type="submit" value="Создать">#', $result);
    }
}
