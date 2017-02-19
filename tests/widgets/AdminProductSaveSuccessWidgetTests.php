<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminProductSaveSuccessWidget;

/**
 * Тестирует класс AdminProductSaveSuccessWidget
 */
class AdminProductSaveSuccessWidgetTests extends TestCase
{
    private $widget;
    
    /**
     * Тестирует свойства AdminProductSaveSuccessWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductSaveSuccessWidget::class);
        
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    public function setUp()
    {
        $this->widget = new AdminProductSaveSuccessWidget();
    }
    
    /**
     * Тестирует метод AdminProductSaveSuccessWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $this->widget->setTemplate('Template');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminProductSaveSuccessWidget::run
     * если пуст AdminProductSaveSuccessWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductSaveSuccessWidget::run
     */
    public function testRun()
    {
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-product-save-success.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p>Продукт успешно сохранен!</p>#', $result);
    }
}
