<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\VisitsMinimalWidget;

/**
 * Тестирует класс VisitsMinimalWidget
 */
class VisitsMinimalWidgetTests extends TestCase
{
    /**
     * Тестирует свойства VisitsMinimalWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(VisitsMinimalWidget::class);
        
        $this->assertTrue($reflection->hasProperty('visitors'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::setVisitors
     * передаю неверный тип параметра
     * @expectedException TypeError
     */
    public function testSetVisitorsError()
    {
        $visitors = new class() {};
        
        $widget = new VisitsMinimalWidget();
        $widget->setVisitors($visitors);
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::setVisitors
     */
    public function testSetVisitors()
    {
        $visitors = 5684;
        
        $widget = new VisitsMinimalWidget();
        $widget->setVisitors($visitors);
        
        $reflection = new \ReflectionProperty($widget, 'visitors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new VisitsMinimalWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new VisitsMinimalWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new VisitsMinimalWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new VisitsMinimalWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::run
     * если пуст VisitsMinimalWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $widget = new VisitsMinimalWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::run
     * если пуст VisitsMinimalWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new VisitsMinimalWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::run
     * если визитов не было
     */
    public function testRunEmpty()
    {
        $widget = new VisitsMinimalWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'visits-minimal.twig');
        
        $result = $widget->run();
        
        //$this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>Сегодня посещений не было</strong></p>#', $result);
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::run
     */
    public function testRun()
    {
        $visitors = 8561;
        
        $widget = new VisitsMinimalWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'visitors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $visitors);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'visits-minimal.twig');
        
        $result = $widget->run();
        
        //$this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>Посещений:</strong> 8561</p>#', $result);
    }
}
