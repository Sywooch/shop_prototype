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
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод ConversionWidget::run
     * если пуст ConversionWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $widget = new ConversionWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод ConversionWidget::run
     * если пуст ConversionWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new ConversionWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
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
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'conversion.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p>Конверсия сегодня: 0%</p>#', $result);
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
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'conversion.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p>Конверсия сегодня: 0%</p>#', $result);
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
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'conversion.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p>Конверсия сегодня: 10.43%</p>#', $result);
    }
}
