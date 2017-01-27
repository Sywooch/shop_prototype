<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ShortCartWidget;
use app\collections\PurchasesCollection;
use app\models\CurrencyModel;

/**
 * Тестирует класс ShortCartWidget
 */
class ShortCartWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ShortCartWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('template'));
        $this->assertTrue($reflection->hasProperty('goods'));
        $this->assertTrue($reflection->hasProperty('cost'));
    }
    
    /**
     * Тестирует метод ShortCartWidget::setPurchases
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = new class() {};
        
        $widget = new ShortCartWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод ShortCartWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = new class() extends PurchasesCollection {};
        
        $widget = new ShortCartWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    /**
     * Тестирует метод ShortCartWidget::setCurrency
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new ShortCartWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод ShortCartWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new ShortCartWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод ShortCartWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new ShortCartWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод ShortCartWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new ShortCartWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод ShortCartWidget::run
     * при отсутствии ShortCartWidget::purchases
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: purchases
     */
    public function testRunEmptyPurchases()
    {
        $widget = new ShortCartWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод ShortCartWidget::run
     * при отсутствии ShortCartWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $purchases = new class() extends PurchasesCollection {};
        
        $widget = new ShortCartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ShortCartWidget::run
     * при отсутствии ShortCartWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $purchases = new class() extends PurchasesCollection {};
        
        $currency = new class() extends CurrencyModel {};
        
        $widget = new ShortCartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ShortCartWidget::run
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
        
        $widget = new ShortCartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'short-cart.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<div class="shortCart">#', $result);
        $this->assertRegExp('#<p>Товаров в корзине: 14, Общая стоимость: 14411,43 MONEY#', $result);
        $this->assertRegExp('#<a href=".+">В корзину</a>#', $result);
        $this->assertRegExp('#<form id="clean-cart-form"#', $result);
        $this->assertRegExp('#<input type="submit" value="Очистить корзину">#', $result);
    }
}
