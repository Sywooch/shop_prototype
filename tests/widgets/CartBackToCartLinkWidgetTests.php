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
        
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод CartBackToCartLinkWidget::run
     * если пуст CartBackToCartLinkWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
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
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'cart-back-to-cart-link.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p class="back-to-cart">#', $result);
        $this->assertRegExp('#<form id="back-to-cart" action=".+" method="GET">#', $result);
        $this->assertRegExp('#<input type="submit" value="Вернуться в покупкам">#', $result);
    }
}
