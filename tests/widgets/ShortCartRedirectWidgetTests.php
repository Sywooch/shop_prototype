<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ShortCartRedirectWidget;
use app\collections\PurchasesCollection;
use app\models\CurrencyModel;

/**
 * Тестирует класс ShortCartRedirectWidget
 */
class ShortCartRedirectWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ShortCartRedirectWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('template'));
        $this->assertTrue($reflection->hasProperty('goods'));
        $this->assertTrue($reflection->hasProperty('cost'));
    }
    
    /**
     * Тестирует метод ShortCartRedirectWidget::setPurchases
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = new class() {};
        
        $widget = new ShortCartRedirectWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод ShortCartRedirectWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = new class() extends PurchasesCollection {};
        
        $widget = new ShortCartRedirectWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    /**
     * Тестирует метод ShortCartRedirectWidget::setCurrency
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new ShortCartRedirectWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод ShortCartRedirectWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new ShortCartRedirectWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод ShortCartRedirectWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new ShortCartRedirectWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод ShortCartRedirectWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new ShortCartRedirectWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод ShortCartRedirectWidget::run
     * при отсутствии ShortCartRedirectWidget::purchases
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: purchases
     */
    public function testRunEmptyPurchases()
    {
        $widget = new ShortCartRedirectWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод ShortCartRedirectWidget::run
     * при отсутствии ShortCartRedirectWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $purchases = new class() extends PurchasesCollection {};
        
        $widget = new ShortCartRedirectWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ShortCartRedirectWidget::run
     * при отсутствии ShortCartRedirectWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $purchases = new class() extends PurchasesCollection {};
        
        $currency = new class() extends CurrencyModel {};
        
        $widget = new ShortCartRedirectWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ShortCartRedirectWidget::run
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
        
        $widget = new ShortCartRedirectWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'short-cart-redirect.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<div class="shortCart">#', $result);
        $this->assertRegExp('#<p>Товаров в корзине: 14, Общая стоимость: 14411,43 MONEY#', $result);
        $this->assertRegExp('#<form id="clean-cart-form"#', $result);
        $this->assertRegExp('#<input type="submit" value="Очистить корзину">#', $result);
    }
}
