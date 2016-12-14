<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CartWidget;
use app\collections\PurchasesCollection;
use app\models\CurrencyModel;

/**
 * Тестирует класс CartWidget
 */
class CartWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CartWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('view'));
        $this->assertTrue($reflection->hasProperty('goods'));
        $this->assertTrue($reflection->hasProperty('cost'));
    }
    
    /**
     * Тестирует метод CartWidget::setPurchases
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = new class() {};
        
        $widget = new CartWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод CartWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = new class() extends PurchasesCollection {};
        
        $widget = new CartWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    /**
     * Тестирует метод CartWidget::setCurrency
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new CartWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод CartWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new CartWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод CartWidget::run
     * при отсутствии CartWidget::purchases
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: purchases
     */
    public function testRunEmptyPurchases()
    {
        $widget = new CartWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CartWidget::run
     * при отсутствии CartWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: currency
     */
    public function testRunEmptyCurrency()
    {
        $purchases = new class() extends PurchasesCollection {};
        
        $widget = new CartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CartWidget::run
     * при отсутствии CartWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $purchases = new class() extends PurchasesCollection {};
        
        $currency = new class() extends CurrencyModel {};
        
        $widget = new CartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CartWidget::run
     */
    public function testRun()
    {
        $purchases = new class() extends PurchasesCollection {
            public function isEmpty(): bool {
                return false;
            }
            public function totalQuantity() {
                return 14;
            }
            public function totalPrice() {
                return 6895.42;
            }
        };
        
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $widget = new CartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'short-cart.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<div id="cart">#', $result);
        $this->assertRegExp('#<p>Товаров в корзине: 14, Общая стоимость: 14411,43 MONEY#', $result);
        $this->assertRegExp('#<a href=".+">В корзину</a>#', $result);
        $this->assertRegExp('#<form id="clean-cart-form"#', $result);
        $this->assertRegExp('#<input type="submit" value="Очистить">#', $result);
    }
}
