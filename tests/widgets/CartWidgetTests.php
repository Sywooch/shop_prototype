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
     * @expectedExceptionMessage Отсутствуют необходимые данные: purchases
     */
    public function testRunErrorPurchases()
    {
        $widget = new CartWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CartWidget::run
     * если CartWidget::purchases пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: purchases
     */
    public function testRunEmptyPurchases()
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
     * при отсутствии CartWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $purchases = new class() extends PurchasesCollection {
            protected $items = [1];
        };
        
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
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $purchases = new class() extends PurchasesCollection {
            protected $items = [1];
        };
        
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
        $items = [
            new class() {
                public $id_product = 1;
                public $quantity = 1;
                public $price = 123.67;
                public $id_color = 1;
                public $id_size = 3;
                public $product;
                public function __construct()
                {
                    $this->product = new class() {
                        public $name = 'Product 1';
                        public $seocode = 'product_1';
                        public $short_description = 'Short description 1';
                        public $images = 'test';
                        public $colors = [['id'=>1, 'color'=>'gray'], ['id'=>4, 'color'=>'yellow']];
                        public $sizes = [['id'=>1, 'size'=>45], ['id'=>3, 'size'=>35.5]];
                    };
                }
            },
            new class() {
                public $id_product = 2;
                public $quantity = 1;
                public $price = 85.00;
                public $id_color = 3;
                public $id_size = 2;
                public $product;
                public function __construct()
                {
                    $this->product = new class() {
                        public $name = 'Product 2';
                        public $seocode = 'product_2';
                        public $short_description = 'Short description 2';
                        public $images = 'test';
                        public $colors = [['id'=>2, 'color'=>'black'], ['id'=>3, 'color'=>'red']];
                        public $sizes = [['id'=>1, 'size'=>45], ['id'=>2, 'size'=>50]];
                    };
                }
            },
        ];
        
        $purchases = new class() extends PurchasesCollection {
            protected $items;
        };
        $reflection = new \ReflectionProperty($purchases, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($purchases, $items);
        
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
        $reflection->setValue($widget, 'cart.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<li class="product-id-1">#', $result);
        $this->assertRegExp('#<a href=".+">Product 1</a>#', $result);
        $this->assertRegExp('#Short description 1#', $result);
        $this->assertRegExp('#<span class="price">258,47 MONEY</span>#', $result);
        $this->assertRegExp('#<form id="form-id-1"#', $result);
        $this->assertRegExp('#<label .+>Quantity</label>#', $result);
        $this->assertRegExp('#<input type="number"#', $result);
        $this->assertRegExp('#<label .+>Id Color</label>#', $result);
        $this->assertRegExp('#<option value="1" selected>gray</option>#', $result);
        $this->assertRegExp('#<option value="4">yellow</option>#', $result);
        $this->assertRegExp('#<label .+>Id Size</label>#', $result);
        $this->assertRegExp('#<option value="3" selected>35.5</option>#', $result);
        $this->assertRegExp('#<option value="1">45</option>#', $result);
        $this->assertRegExp('#<input type="submit" value="Обновить">#', $result);
        $this->assertRegExp('#<form id="form-id-delete-1"#', $result);
        $this->assertRegExp('#<input type="submit" value="Удалить">#', $result);
        
        $this->assertRegExp('#<li class="product-id-2">#', $result);
        $this->assertRegExp('#<a href=".+">Product 2</a>#', $result);
        $this->assertRegExp('#Short description 2#', $result);
        $this->assertRegExp('#<span class="price">177,65 MONEY</span>#', $result);
        $this->assertRegExp('#<img src=".+" alt="">#', $result);
        $this->assertRegExp('#<form id="form-id-2"#', $result);
        $this->assertRegExp('#<option value="2">black</option>#', $result);
        $this->assertRegExp('#<option value="3" selected>red</option>#', $result);
        $this->assertRegExp('#<option value="1">45</option>#', $result);
        $this->assertRegExp('#<option value="2" selected>50</option>#', $result);
    }
}
