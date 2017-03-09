<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\PurchaseSaveInfoWidget;

/**
 * Тестирует класс PurchaseSaveInfoWidget
 */
class PurchaseSaveInfoWidgetTests extends TestCase
{
    /**
     * Тестирует свойства PurchaseSaveInfoWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchaseSaveInfoWidget::class);
        
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод PurchaseSaveInfoWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new PurchaseSaveInfoWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод PurchaseSaveInfoWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new PurchaseSaveInfoWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод PurchaseSaveInfoWidget::run
     * если пуст PurchaseSaveInfoWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $widget = new PurchaseSaveInfoWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод PurchaseSaveInfoWidget::run
     */
    public function testRun()
    {
        $widget = new PurchaseSaveInfoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'paragraph.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p>Товар успешно добавлен в корзину!</p>#', $result);
    }
}
