<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\VisitsMinimalWidget;
use app\models\{VisitorsCounterInterface,
    VisitorsCounterModel};

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
        
        $this->assertTrue($reflection->hasProperty('visits'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::setVisits
     * передаю неверный тип параметра
     * @expectedException TypeError
     */
    public function testSetVisitsError()
    {
        $visits = 'a2';
        
        $widget = new VisitsMinimalWidget();
        $widget->setVisits($visits);
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::setVisits
     */
    public function testSetVisits()
    {
        $visits = 18564;
        
        $widget = new VisitsMinimalWidget();
        $widget->setVisits($visits);
        
        $reflection = new \ReflectionProperty($widget, 'visits');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertEquals(18564, $result);
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
     * если пуст VisitsMinimalWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new VisitsMinimalWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::run
     * если визитов не было
     */
    public function testRunEmpty()
    {
        $widget = new VisitsMinimalWidget();
        
        $reflection = new \ReflectionProperty($widget, 'visits');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 0);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'visits-minimal.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Сегодня посещений не было</strong></p>#', $result);
    }
    
    /**
     * Тестирует метод VisitsMinimalWidget::run
     */
    public function testRun()
    {
        $widget = new VisitsMinimalWidget();
        
        $reflection = new \ReflectionProperty($widget, 'visits');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 8561);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'visits-minimal.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Посещений:</strong> 8561</p>#', $result);
    }
}
