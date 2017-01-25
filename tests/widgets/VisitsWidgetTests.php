<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\VisitsWidget;

/**
 * Тестирует класс VisitsWidget
 */
class VisitsWidgetTests extends TestCase
{
    /**
     * Тестирует свойства VisitsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(VisitsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('visitors'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод VisitsWidget::setVisitors
     * передаю неверный тип параметра
     * @expectedException TypeError
     */
    public function testSetVisitorsError()
    {
        $visitors = new class() {};
        
        $widget = new VisitsWidget();
        $widget->setVisitors($visitors);
    }
    
    /**
     * Тестирует метод VisitsWidget::setVisitors
     */
    public function testSetVisitors()
    {
        $visitors = new class() {};
        
        $widget = new VisitsWidget();
        $widget->setVisitors([$visitors]);
        
        $reflection = new \ReflectionProperty($widget, 'visitors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод VisitsWidget::run
     * если пуст VisitsWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $widget = new VisitsWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод VisitsWidget::run
     * если пуст VisitsWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new VisitsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод VisitsWidget::run
     * если визитов не было
     */
    public function testRunEmpty()
    {
        $widget = new VisitsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'visits.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p>Сегодня посещений не было</p>#', $result);
    }
    
    /**
     * Тестирует метод VisitsWidget::run
     */
    public function testRun()
    {
        $visitors = [
            new class() {
                public $date = 1483609405;
                public $counter = 8976;
            },
            new class() {
                public $date = 1484290868;
                public $counter = 5690;
            },
        ];
        
        $widget = new VisitsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'visitors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $visitors);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'visits.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#13 янв. 2017 г. - 5690 посетителей<br>#', $result);
        $this->assertRegExp('#5 янв. 2017 г. - 8976 посетителей<br>#', $result);
    }
}
