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
        
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод CartCheckoutLinkWidget::run
     * если пуст CartCheckoutLinkWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
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
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'cart-checkout-link.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p class="checkout-link">#', $result);
        $this->assertRegExp('#<form id="cart-сheckout-ajax-link" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="submit" value="Оформить заказ">#', $result);
    }
}
