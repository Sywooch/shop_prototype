<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\FrontendFooterWidget;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс FrontendFooterWidget
 */
class FrontendFooterWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new FrontendFooterWidget();
    }
    
    /**
     * Тестирует свойства FrontendFooterWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(FrontendFooterWidget::class);
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод FrontendFooterWidget::setForm
     */
    public function testSetForm()
    {
        $mock = new class() extends AbstractBaseForm {};
        
        $this->widget->setForm($mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод FrontendFooterWidget::setTemplate
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
     * Тестирует метод FrontendFooterWidget::run
     * если пуст FrontendFooterWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод FrontendFooterWidget::run
     * если пуст FrontendFooterWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод FrontendFooterWidget::run
     */
    public function testRun()
    {
        $form = new class() extends AbstractBaseForm {
            public $email;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'user-mailing-form.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<ul class="footer-links">#', $result);
        $this->assertRegExp('#<li><a href=".+">.+</a></li>#', $result);
        $this->assertRegExp('#<div class="footer-mailings-form">#', $result);
        $this->assertRegExp('#<form id="footer-mailings-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[email\]" placeholder=".+">#', $result);
    }
}
