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
        
        $this->assertTrue($reflection->hasProperty('period'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод CalendarWidget::getCalendar
     */
    public function testGetCalendar()
    {
        $widget = new CalendarWidget();
        
        $reflection = new \ReflectionProperty($widget, 'period');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, new \DateTime());
        
        $reflection = new \ReflectionMethod($widget, 'getCalendar');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertInternalType('array', $result[0]);
        
        $this->assertArrayHasKey('number', $result[0][6]);
        $this->assertArrayHasKey('timestamp', $result[0][6]);
        $this->assertArrayHasKey('format', $result[0][6]);
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
        
        $this->assertContains(\Yii::t('base', 'Mon'), $result);
        $this->assertContains(\Yii::t('base', 'Tue'), $result);
        $this->assertContains(\Yii::t('base', 'Wed'), $result);
        $this->assertContains(\Yii::t('base', 'Thu'), $result);
        $this->assertContains(\Yii::t('base', 'Fri'), $result);
        $this->assertContains(\Yii::t('base', 'Sat'), $result);
        $this->assertContains(\Yii::t('base', 'Sun'), $result);
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
     * Тестирует метод CalendarWidget::setPeriod
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPeriodTypeError()
    {
        $period = new class() {};
        
        $widget = new CalendarWidget();
        $widget->setPeriod($period);
    }
    
    /**
     * Тестирует метод CalendarWidget::setPeriod
     */
    public function testSetPeriod()
    {
        $period = new \DateTime();
        
        $widget = new CalendarWidget();
        $widget->setPeriod($period);
        
        $reflection = new \ReflectionProperty($widget, 'period');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(\DateTime::class, $result);
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
     * если пуст CalendarWidget::period
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: period
     */
    public function testRunEmptyYear()
    {
        $widget = new CalendarWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CalendarWidget::run
     * если пуст CalendarWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $widget = new CalendarWidget();
        
        $reflection = new \ReflectionProperty($widget, 'period');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CalendarWidget::run
     * если выбранный месяц равен текущему
     */
    public function testRunCurrentMonth()
    {
        $widget = new CalendarWidget();
        
        $reflection = new \ReflectionProperty($widget, 'period');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, new \DateTime());
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'calendar.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<div class="calendar">#', $result);
        $this->assertRegExp('#<a href=".+" data-timestamp="[0-9]{10}" class="calendar-href-prev"><<</a>#', $result);
        $this->assertRegExp('#[a-zA-Z]+ [0-9]{4}#', $result);
        //$this->assertRegExp('#<a href=".+" data-timestamp="[0-9]{10}" class="calendar-href-next">>></a>#', $result);
        $this->assertRegExp('#<td>Пн</td>#', $result);
        //$this->assertRegExp('#<td data-timestamp="" data-format=""></td>#', $result);
        $this->assertRegExp('#<td data-timestamp="[0-9]{10}" data-format="[0-9]{1,2} .+ [0-9]{4} г\.">[0-9]{1,2}</td>#', $result);
    }
    
    /**
     * Тестирует метод CalendarWidget::run
     */
    public function testRun()
    {
        $widget = new CalendarWidget();
        
        $reflection = new \ReflectionProperty($widget, 'period');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, (new \DateTime())->setTimestamp(time() - (60 * 60 * 24 * 52)));
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'calendar.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<div class="calendar">#', $result);
        $this->assertRegExp('#<a href=".+" data-timestamp="[0-9]{10}" class="calendar-href-prev"><<</a>#', $result);
        $this->assertRegExp('#[a-zA-Z]+ [0-9]{4}#', $result);
        $this->assertRegExp('#<a href=".+" data-timestamp="[0-9]{10}" class="calendar-href-next">>></a>#', $result);
        $this->assertRegExp('#<td>Пн</td>#', $result);
        $this->assertRegExp('#<td data-timestamp="" data-format=""></td>#', $result);
        $this->assertRegExp('#<td data-timestamp="[0-9]{10}" data-format="[0-9]{1,2} .+ [0-9]{4} г\.">[0-9]{1,2}</td>#', $result);
    }
}
