<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminAddProductButtonWidget;

/**
 * Тестирует класс AdminAddProductButtonWidget
 */
class AdminAddProductButtonWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AdminAddProductButtonWidget();
    }
    
    /**
     * Тестирует свойства AdminAddProductButtonWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminAddProductButtonWidget::class);
        
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminAddProductButtonWidget::setTemplate
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
     * Тестирует метод AdminAddProductButtonWidget::run
     * если пуст AdminAddProductButtonWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $this->widget->run();
    }
    
    /**
    /**
     * Тестирует метод AdminAddProductButtonWidget::run
     */
    public function testRun()
    {
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-add-product-button.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<a href=".+">Добавить товар</a>#', $result);
    }
}
