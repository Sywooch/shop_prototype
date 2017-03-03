<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CartWidget;
use app\collections\PurchasesCollection;
use app\models\CurrencyModel;
use app\forms\{AbstractBaseForm,
    PurchaseForm};

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
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
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
     * Тестирует метод CartWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends AbstractBaseForm {};
        
        $widget = new CartWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод CartWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new CartWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CartWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new CartWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
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
     * при отсутствии CartWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
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
     * при отсутствии CartWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $purchases = new class() extends PurchasesCollection {
            protected $items = [1];
        };
        
        $currency = new class() extends CurrencyModel {};
        
        $form = new class() extends PurchaseForm {};
        
        $widget = new CartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CartWidget::run
     * при отсутствии CartWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $purchases = new class() extends PurchasesCollection {
            protected $items = [1];
        };
        
        $currency = new class() extends CurrencyModel {};
        
        $form = new class() extends AbstractBaseForm {};
        
        $widget = new CartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
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
        
        $form = new class() extends AbstractBaseForm {
            public $id_color;
            public $id_size;
            public $quantity;
        };
        
        $widget = new CartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'cart.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<li class="product-id-1">#', $result);
        $this->assertRegExp('#<a href=".+">Product 1</a>#', $result);
        $this->assertRegExp('#Short description 1#', $result);
        $this->assertRegExp('#<span class="price">258,47 MONEY</span>#', $result);
        $this->assertRegExp('#<form id="update-product-form-1"#', $result);
        $this->assertRegExp('#<form id="delete-product-form-1"#', $result);
        $this->assertRegExp('#<form id="update-product-form-2"#', $result);
        $this->assertRegExp('#<form id="delete-product-form-2"#', $result);
        $this->assertRegExp('#<label .+>Quantity</label>#', $result);
        $this->assertRegExp('#<input type="number"#', $result);
        $this->assertRegExp('#<label .+>Id Color</label>#', $result);
        $this->assertRegExp('#<option value="1" selected>gray</option>#', $result);
        $this->assertRegExp('#<option value="4">yellow</option>#', $result);
        $this->assertRegExp('#<label .+>Id Size</label>#', $result);
        $this->assertRegExp('#<option value="3" selected>35.5</option>#', $result);
        $this->assertRegExp('#<option value="1">45</option>#', $result);
        $this->assertRegExp('#<input type="submit" value="Обновить">#', $result);
        $this->assertRegExp('#<input type="submit" value="Удалить">#', $result);
        $this->assertRegExp('#<li class="product-id-2">#', $result);
        $this->assertRegExp('#<a href=".+">Product 2</a>#', $result);
        $this->assertRegExp('#Short description 2#', $result);
        $this->assertRegExp('#<span class="price">177,65 MONEY</span>#', $result);
        $this->assertRegExp('#<img src=".+" alt="">#', $result);
        $this->assertRegExp('#<option value="2">black</option>#', $result);
        $this->assertRegExp('#<option value="3" selected>red</option>#', $result);
        $this->assertRegExp('#<option value="1">45</option>#', $result);
        $this->assertRegExp('#<option value="2" selected>50</option>#', $result);
    }
}
