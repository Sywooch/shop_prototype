<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CartBackToCartLinkWidget;

/**
 * Тестирует класс CartBackToCartLinkWidget
 */
class CartBackToCartLinkWidgetTests extends TestCase
{
    /**
     * Тестирует свойства CartBackToCartLinkWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CartBackToCartLinkWidget::class);
        
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод CartBackToCartLinkWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new CartBackToCartLinkWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод CartBackToCartLinkWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new CartBackToCartLinkWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CartBackToCartLinkWidget::run
     * если пуст CartBackToCartLinkWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new CartBackToCartLinkWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CartBackToCartLinkWidget::run
     */
    public function testRun()
    {
        $widget = new CartBackToCartLinkWidget();
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'cart-back-to-cart-link.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p class="back-to-cart">#', $result);
        $this->assertRegExp('#<form id="back-to-cart" action=".+" method="GET">#', $result);
        $this->assertRegExp('#<input type="submit" value="Вернуться в покупкам">#', $result);
    }
}
