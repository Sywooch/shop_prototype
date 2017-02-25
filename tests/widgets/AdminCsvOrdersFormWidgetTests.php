<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminCsvOrdersFormWidget;

/**
 * Тестирует класс AdminCsvOrdersFormWidget
 */
class AdminCsvOrdersFormWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AdminCsvOrdersFormWidget();
    }
    
    /**
     * Тестирует свойства AdminCsvOrdersFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCsvOrdersFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
        $this->assertTrue($reflection->hasProperty('isAllowed'));
    }
    
    /**
     * Тестирует метод AdminCsvOrdersFormWidget::setHeader
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
     * Тестирует метод AdminCsvOrdersFormWidget::setTemplate
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
     * Тестирует метод AdminCsvOrdersFormWidget::run
     * если пуст AdminCsvOrdersFormWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCsvOrdersFormWidget::run
     * если пуст AdminCsvOrdersFormWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCsvOrdersFormWidget::run
     */
    public function testRun()
    {
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-csv-orders-form.twig');
        
        $reflection = new \ReflectionProperty($this->widget, 'isAllowed');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, true);
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<div class="get-csv">#', $result);
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p class="csv-success"></p>#', $result);
        $this->assertRegExp('#<form id="admin-scv-orders-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="submit" value="Получить ссылку">#', $result);
    }
}
