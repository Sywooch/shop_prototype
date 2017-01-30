<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CalendarWidget;

/**
 * Тестирует класс CalendarWidget
 */
class CalendarWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CalendarWidget::class);
        
        $this->assertTrue($reflection->hasProperty('year'));
        $this->assertTrue($reflection->hasProperty('month'));
        $this->assertTrue($reflection->hasProperty('period'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод CalendarWidget::getDayNames
     */
    public function testGetDayNames()
    {
        $widget = new CalendarWidget();
        $result = $widget->getDayNames();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод CalendarWidget::getYear
     */
    public function testGetYear()
    {
        $widget = new CalendarWidget();
        
        $reflection = new \ReflectionProperty($widget, 'period');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, new \DateTime('2017-1-1'));
        
        $result = $widget->getYear();
        
        $this->assertEquals(2017, $result);
    }
    
    /**
     * Тестирует метод CalendarWidget::getMonth
     */
    public function testGetMonth()
    {
        $widget = new CalendarWidget();
        
        $reflection = new \ReflectionProperty($widget, 'period');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, new \DateTime('2017-1-1'));
        
        $result = $widget->getMonth();
        
        $this->assertEquals(1, $result);
    }
    
    /**
     * Тестирует метод CalendarWidget::getMonthVerb
     */
    public function testGetMonthVerb()
    {
        $widget = new CalendarWidget();
        
        $reflection = new \ReflectionProperty($widget, 'period');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, new \DateTime('2017-1-1'));
        
        $result = $widget->getMonthVerb();
        
        $this->assertEquals('January', $result);
    }
    
    /**
     * Тестирует метод CalendarWidget::getDaysInMonth
     */
    public function testGetDaysInMonth()
    {
        $widget = new CalendarWidget();
        
        $reflection = new \ReflectionProperty($widget, 'period');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, new \DateTime('2017-1-1'));
        
        $result = $widget->getDaysInMonth();
        
        $this->assertEquals(31, $result);
    }
    
    /**
     * Тестирует метод CalendarWidget::getRunningDay
     */
    public function testGetRunningDay()
    {
        $widget = new CalendarWidget();
        
        $reflection = new \ReflectionProperty($widget, 'period');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, new \DateTime('2017-1-1'));
        
        $result = $widget->getRunningDay();
        
        $this->assertEquals(7, $result);
    }
    
    /**
     * Тестирует метод CalendarWidget::setYear
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetYearTypeError()
    {
        $year = 'a2017';
        
        $widget = new CalendarWidget();
        $widget->setYear($year);
    }
    
    /**
     * Тестирует метод CalendarWidget::setYear
     * передаю не корректный номер года
     * @expectedException ErrorException
     * @@expectedExceptionMessage Получен неверный тип данных вместо: year
     */
    public function testSetYearError()
    {
        $year = 201;
        
        $widget = new CalendarWidget();
        $widget->setYear($year);
    }
    
    /**
     * Тестирует метод CalendarWidget::setYear
     */
    public function testSetYear()
    {
        $year = 2017;
        
        $widget = new CalendarWidget();
        $widget->setYear($year);
        
        $reflection = new \ReflectionProperty($widget, 'year');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertEquals(2017, $result);
    }
    
    /**
     * Тестирует метод CalendarWidget::setMonth
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetMonthTypeError()
    {
        $month = 'a2';
        
        $widget = new CalendarWidget();
        $widget->setMonth($month);
    }
    
    /**
     * Тестирует метод CalendarWidget::setMonth
     * передаю не корректный номер года
     * @expectedException ErrorException
     * @@expectedExceptionMessage Получен неверный тип данных вместо: month
     */
    public function testSetMonthError()
    {
        $month = 201;
        
        $widget = new CalendarWidget();
        $widget->setMonth($month);
    }
    
    /**
     * Тестирует метод CalendarWidget::setMonth
     */
    public function testSetMonth()
    {
        $month = 2;
        
        $widget = new CalendarWidget();
        $widget->setMonth($month);
        
        $reflection = new \ReflectionProperty($widget, 'month');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertEquals(2, $result);
    }
    
    /**
     * Тестирует метод CalendarWidget::setTemplate
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateTypeError()
    {
        $template = null;
        
        $widget = new CalendarWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод CalendarWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'template';
        
        $widget = new CalendarWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertEquals('template', $result);
    }
    
    /**
     * Тестирует метод CalendarWidget::run
     */
    public function testRun()
    {
        $widget = new CalendarWidget([
            'year'=>2017,
            'month'=>1
        ]);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'calendar.twig');
        
        $result = $widget->run();
    }
}
