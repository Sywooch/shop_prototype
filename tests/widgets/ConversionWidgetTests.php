<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ConversionWidget;

/**
 * Тестирует класс ConversionWidget
 */
class ConversionWidgetTests extends TestCase
{
    /**
     * Тестирует свойства ConversionWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ConversionWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('visits'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод ConversionWidget::setPurchases
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = null;
        
        $widget = new ConversionWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод ConversionWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = 25;
        
        $widget = new ConversionWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('int', $result);
    }
    
    /**
     * Тестирует метод ConversionWidget::setVisits
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetVisitsError()
    {
        $visits = null;
        
        $widget = new ConversionWidget();
        $widget->setVisits($visits);
    }
    
    /**
     * Тестирует метод ConversionWidget::setVisits
     */
    public function testSetVisits()
    {
        $visits = 18;
        
        $widget = new ConversionWidget();
        $widget->setVisits($visits);
        
        $reflection = new \ReflectionProperty($widget, 'visits');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('int', $result);
    }
    
    /**
     * Тестирует метод ConversionWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new ConversionWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод ConversionWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new ConversionWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод ConversionWidget::run
     * если пуст ConversionWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new ConversionWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод ConversionWidget::run
     * если визитов не было
     */
    public function testRunEmptyVisits()
    {
        $widget = new ConversionWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 5);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'conversion.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Конверсия</strong>: 0%</p>#', $result);
    }
    
    /**
     * Тестирует метод ConversionWidget::run
     * если покупок не было
     */
    public function testRunEmptyPurchases()
    {
        $widget = new ConversionWidget();
        
        $reflection = new \ReflectionProperty($widget, 'visits');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 508);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'conversion.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Конверсия</strong>: 0%</p>#', $result);
    }
    
    /**
     * Тестирует метод ConversionWidget::run
     */
    public function testRun()
    {
        $widget = new ConversionWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 53);
        
        $reflection = new \ReflectionProperty($widget, 'visits');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 508);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'conversion.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Конверсия</strong>: 10.43%</p>#', $result);
    }
}
