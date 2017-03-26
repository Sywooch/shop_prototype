<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ModProductsWidget;
use app\collections\{CollectionInterface,
    ProductsCollection};
use app\models\CurrencyModel;
use app\controllers\ProductsListController;

/**
 * Тестирует класс ModProductsWidget
 */
class ModProductsWidgetTests extends TestCase
{
    public $widget;
    
    public function setUp()
    {
        $this->widget = new ModProductsWidget();
    }
    
    /**
     * Тестирует свойства ModProductsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ModProductsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('products'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод ModProductsWidget::setProducts
     */
    public function testSetProducts()
    {
        $collection = new class() extends ProductsCollection {};
        
        $this->widget->setProducts($collection);
        
        $reflection = new \ReflectionProperty($this->widget, 'products');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод ModProductsWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $this->widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод ModProductsWidget::setTemplate
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
     * Тестирует метод ModProductsWidget::run
     * если пуст ModProductsWidget::products
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: products
     */
    public function testRunEmptyProducts()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModProductsWidget::run
     * если пуст ModProductsWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $products = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $products);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModProductsWidget::run
     * если пуст ModProductsWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $products = new class() {};
        $currency = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $products);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $currency);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModProductsWidget::run
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
        
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 12.865412;
            public $code = 'MONEY';
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $products);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $currency);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'products-list-mod.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<ul class="products-items">#', $result);
        $this->assertRegExp('#<li class="product-id">#', $result);
        $this->assertRegExp('# <a href=".+"><img src=".+" height="200" alt=""></a>#', $result);
        $this->assertRegExp('#<div class="product-name disable"><a href=".+" class="product-text-link">Black mood shoes</a>#', $result);
        $this->assertRegExp('#<span class="price">1591,07 </span>#', $result);
    }
}
