<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CartCheckoutLinkWidget;

/**
 * Тестирует класс CartCheckoutLinkWidget
 */
class CartCheckoutLinkWidgetTests extends TestCase
{
    /**
     * Тестирует свойства CartCheckoutLinkWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CartCheckoutLinkWidget::class);
        
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод CartCheckoutLinkWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new CartCheckoutLinkWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод CartCheckoutLinkWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new CartCheckoutLinkWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CartCheckoutLinkWidget::run
     * если пуст CartCheckoutLinkWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new CartCheckoutLinkWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CartCheckoutLinkWidget::run
     */
    public function testRun()
    {
        $widget = new CartCheckoutLinkWidget();
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'cart-checkout-link.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p class="checkout-link">#', $result);
        $this->assertRegExp('#<form id="cart-сheckout-ajax-link" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="submit" value="Оформить заказ">#', $result);
    }
}
