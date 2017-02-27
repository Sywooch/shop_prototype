<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminCsvCommentsFormWidget;

/**
 * Тестирует класс AdminCsvCommentsFormWidget
 */
class AdminCsvCommentsFormWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AdminCsvCommentsFormWidget();
    }
    
    /**
     * Тестирует свойства AdminCsvCommentsFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCsvCommentsFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
        $this->assertTrue($reflection->hasProperty('isAllowed'));
    }
    
    /**
     * Тестирует метод AdminCsvCommentsFormWidget::setHeader
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
     * Тестирует метод AdminCsvCommentsFormWidget::setTemplate
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
     * Тестирует метод AdminCsvCommentsFormWidget::run
     * если пуст AdminCsvCommentsFormWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $this->widget->run();
    }
    
    
    /**
     * Тестирует метод AdminCsvCommentsFormWidget::run
     * если пуст AdminCsvCommentsFormWidget::template
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
     * Тестирует метод AdminCsvCommentsFormWidget::run
     */
    public function testRun()
    {
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-csv-comments-form.twig');
        
        $reflection = new \ReflectionProperty($this->widget, 'isAllowed');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, true);
        
        $result = $this->widget->run();

        $this->assertRegExp('#<div class="get-csv">#', $result);
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p class="csv-success"></p>#', $result);
        $this->assertRegExp('#<form id="admin-scv-comments-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="submit" value="Получить ссылку">#', $result);
    }
}
