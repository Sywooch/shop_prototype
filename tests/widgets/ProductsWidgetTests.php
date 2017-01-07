<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ProductsWidget;
use app\collections\{ProductsCollectionInterface,
    ProductsCollection};
use app\models\CurrencyModel;
use app\controllers\ProductsListController;

/**
 * Тестирует класс ProductsWidget
 */
class ProductsWidgetTests extends TestCase
{
    /**
     * Тестирует свойства ProductsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('products'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод ProductsWidget::setProducts
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProductsError()
    {
        $collection = new class() {};
        
        $widget = new ProductsWidget();
        $widget->setProducts($collection);
    }
    
    /**
     * Тестирует метод ProductsWidget::setProducts
     */
    public function testSetProducts()
    {
        $collection = new class() extends ProductsCollection {};
        
        $widget = new ProductsWidget();
        $widget->setProducts($collection);
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(ProductsCollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод ProductsWidget::setCurrency
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new ProductsWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод ProductsWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new ProductsWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод ProductsWidget::run
     * если пуст ProductsWidget::products
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: products
     */
    public function testRunEmptyProducts()
    {
        $widget = new ProductsWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductsWidget::run
     * если пуст ProductsWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $products = new class() {};
        
        $widget = new ProductsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $products);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductsWidget::run
     * если пуст ProductsWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $products = new class() {};
        $currency = new class() {};
        
        $widget = new ProductsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $products);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductsWidget::run
     */
    public function testRun()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $products = [
            new class() {
                public $id = 1;
                public $name = 'Black mood shoes';
                public $seocode = 'black-mood-shoes';
                public $short_description = 'This Black mood shoes for crazy bunchers';
                public $price = 123.67;
                public $images = 'test';
            },
            new class() {
                public $id = 2;
                public $name = 'Purple woman shirt';
                public $seocode = 'purple-woman-shirt';
                public $short_description = 'Nice shirt for nice women';
                public $price = 32.14;
                public $images = 'test';
            },
        ];
        
        $currency = new class() {
            public $exchange_rate = 12.865412;
            public $code = 'MONEY';
        };
        
        $widget = new ProductsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $products);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'products-list.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<ol>#', $result);
        $this->assertRegExp('#<li class="product-id-1">#', $result);
        $this->assertRegExp('#<li class="product-id-2">#', $result);
        $this->assertRegExp('#<a href=".+">Black mood shoes</a>#', $result);
        $this->assertRegExp('#This Black mood shoes for crazy bunchers#', $result);
        $this->assertRegExp('#Цена: <span class="price">1591,07 MONEY</span>#', $result);
        $this->assertRegExp('#<img src=".+" alt="">#', $result);
        $this->assertRegExp('#<a href=".+">Purple woman shirt</a>#', $result);
        $this->assertRegExp('#Nice shirt for nice women#', $result);
        $this->assertRegExp('#Цена: <span class="price">413,49 MONEY</span>#', $result);
    }
}
