<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminTodayOrdersMinimalWidget;

/**
 * Тестирует класс AdminTodayOrdersMinimalWidget
 */
class AdminTodayOrdersMinimalWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AdminTodayOrdersMinimalWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminTodayOrdersMinimalWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminTodayOrdersMinimalWidget::setPurchases
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = new class() {};
        
        $widget = new AdminTodayOrdersMinimalWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод AdminTodayOrdersMinimalWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = 2;
        
        $widget = new AdminTodayOrdersMinimalWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод AdminTodayOrdersMinimalWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new AdminTodayOrdersMinimalWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод AdminTodayOrdersMinimalWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new AdminTodayOrdersMinimalWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminTodayOrdersMinimalWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AdminTodayOrdersMinimalWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AdminTodayOrdersMinimalWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AdminTodayOrdersMinimalWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminTodayOrdersMinimalWidget::run
     * если пуст AdminTodayOrdersMinimalWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $widget = new AdminTodayOrdersMinimalWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminTodayOrdersMinimalWidget::run
     * если пуст AdminTodayOrdersMinimalWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $widget = new AdminTodayOrdersMinimalWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminTodayOrdersMinimalWidget::run
     * если нет оформленных покупок
     */
    public function testRunNotPurchases()
    {
        $widget = new AdminTodayOrdersMinimalWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'admin-today-orders-minimal.twig');
        
        $result = $widget->run();
        
        //$this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p>Сегодня заказов нет</p>#', $result);
    }
    
    /**
     * Тестирует метод AdminTodayOrdersMinimalWidget::run
     * если есть оформленные покупки
     */
    public function testRunExistProcessedPurchases()
    {
        $purchases = 3;
        
        $widget = new AdminTodayOrdersMinimalWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'admin-today-orders-minimal.twig');
        
        $result = $widget->run();
        
        //$this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>Заказы</strong>: 3</p>#', $result);
    }
}
