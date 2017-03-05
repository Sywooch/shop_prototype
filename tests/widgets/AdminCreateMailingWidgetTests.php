<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminCreateMailingWidget;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminCreateMailingWidget
 */
class AdminCreateMailingWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AdminCreateMailingWidget();
    }
    
    /**
     * Тестирует свойства AdminCreateMailingWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCreateMailingWidget::class);
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminCreateMailingWidget::setForm
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
     * Тестирует метод AdminCreateMailingWidget::setHeader
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
     * Тестирует метод AdminCreateMailingWidget::setTemplate
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
     * Тестирует метод AdminCreateMailingWidget::run
     * если пуст AdminCreateMailingWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $result = $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCreateMailingWidget::run
     * если пуст AdminCreateMailingWidget::header
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
     * Тестирует метод AdminCreateMailingWidget::run
     * если пуст AdminCreateMailingWidget::template
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
     * Тестирует метод AdminCreateMailingWidget::run
     */
    public function testRun()
    {
        $form = new class() extends AbstractBaseForm {
            public $name;
            public $description;
            public $active;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, 'admin-create-mailing.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="mailing-create-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[name\]">#', $result);
        $this->assertRegExp('#<textarea id=".+" class="form-control" name=".+\[description\]" rows="[0-9]{1,2}" cols="[0-9]{1,2}"></textarea>#', $result);
        $this->assertRegExp('#<input type="checkbox" id=".+" name=".+\[active\]" value="1"> Active</label>#', $result);
        $this->assertRegExp('#<input type="submit" value="Создать">#', $result);
    }
}
