<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ModShortCartWidget;
use app\collections\{PurchasesCollection,
    PurchasesCollectionInterface};
use app\models\{CurrencyModel,
    CurrencyInterface};

/**
 * Тестирует класс ModShortCartWidget
 */
class ModShortCartWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new ModShortCartWidget();
    }
    
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ModShortCartWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('template'));
        $this->assertTrue($reflection->hasProperty('goods'));
        $this->assertTrue($reflection->hasProperty('cost'));
    }
    
    /**
     * Тестирует метод ModShortCartWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = new class() extends PurchasesCollection {};
        
        $this->widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($this->widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод ModShortCartWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $this->widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(CurrencyInterface::class, $result);
    }
    
    /**
     * Тестирует метод ModShortCartWidget::setTemplate
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
     * Тестирует метод ModShortCartWidget::run
     * при отсутствии ModShortCartWidget::purchases
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: purchases
     */
    public function testRunEmptyPurchases()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModShortCartWidget::run
     * при отсутствии ModShortCartWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModShortCartWidget::run
     * при отсутствии ModShortCartWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModShortCartWidget::run
     * если корзина пуста
     */
    public function testRunEmptyCart()
    {
        $purchases = new class() extends PurchasesCollection {
            public function isEmpty(): bool {
                return false;
            }
            public function totalQuantity() {
                return 0;
            }
            public function totalPrice() {
                return 0;
            }
        };
        
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $symbol = '&#163;';
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $purchases);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $currency);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'short-cart-mod.twig');
        
        $result = $this->widget->run();
        
        $result = preg_replace(['#\s{2,}#', '#> #', '# <#'], [' ', '>', '<'], $result);
        
        $this->assertRegExp('#<div id="short-cart">Корзина<span class="separate">/</span>0,00 &\#163;</div>#', $result);
    }
    
    /**
     * Тестирует метод ModShortCartWidget::run
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
            public $symbol = '&#163;';
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $purchases);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $currency);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'short-cart-mod.twig');
        
        $result = $this->widget->run();
        
        $result = preg_replace(['#\s{2,}#', '#> #', '# <#'], [' ', '>', '<'], $result);
        
        $this->assertRegExp('#<div id="short-cart"><a href=".+">Корзина 14<span class="separate">/</span>14411,43 &\#163;
</a></div>#', $result);
    }
}
