<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ProductDetailWidget;
use app\models\{CurrencyModel,
    ProductsModel};

/**
 * Тестирует класс ProductDetailWidget
 */
class ProductDetailWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductDetailWidget::class);
        
        $this->assertTrue($reflection->hasProperty('product'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setProduct
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProductError()
    {
        $product = new class() {};
        
        $widget = new ProductDetailWidget();
        $widget->setProduct($product);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setProduct
     */
    public function testSetProduct()
    {
        $product = new class() extends ProductsModel {};
        
        $widget = new ProductDetailWidget();
        $widget->setProduct($product);
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setCurrency
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new ProductDetailWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new ProductDetailWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод ProductDetailWidget::run
     * если пуст ProductDetailWidget::product
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: product
     */
    public function testRunEmptyProduct()
    {
        $widget = new ProductDetailWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductDetailWidget::run
     * если пуст ProductDetailWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: currency
     */
    public function testRunEmptyCurrency()
    {
        $product = new class() extends ProductsModel {};
        
        $widget = new ProductDetailWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $product);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductDetailWidget::run
     * если пуст ProductDetailWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $product = new class() extends ProductsModel {};
        $currency = new class() extends CurrencyModel {};
        
        $widget = new ProductDetailWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $product);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductDetailWidget::run
     */
    public function testRun()
    {
        $colors = [
            ['id'=>1, 'color'=>'black'], 
            ['id'=>2, 'color'=>'yellow']
        ];
        
        $sizes = [
            ['id'=>1, 'size'=>23], 
            ['id'=>2, 'size'=>45.5]
        ];
        
        $product = new class() {
            public $name = 'Name';
            public $description = 'Description';
            public $images = 'test';
            public $price = 85.78;
            public $code = 'TEST';
            public $colors;
            public $sizes;
            
        };
        
        $reflection = new \ReflectionProperty($product, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($product, $colors);
        
        $reflection = new \ReflectionProperty($product, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($product, $sizes);
        
        $currency = new class() {
            public $exchange_rate = 12.45;
            public $code = 'MONEY';
        };
        
        $widget = new ProductDetailWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $product);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'product-detail.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('/<h1>Name<\/h1>/', $result);
        $this->assertRegExp('/<p>Description<\/p>/', $result);
        $this->assertRegExp('/<img src=".+" alt=""><br\/>/', $result);
        $this->assertRegExp('/<p><strong>Цвета:<\/strong><\/p>/', $result);
        $this->assertRegExp('/<li>black<\/li>/', $result);
        $this->assertRegExp('/<li>yellow<\/li>/', $result);
        $this->assertRegExp('/<p><strong>Размеры:<\/strong><\/p>/', $result);
        $this->assertRegExp('/<li>23<\/li>/', $result);
        $this->assertRegExp('/<li>45.5<\/li>/', $result);
        $this->assertRegExp('/<p><strong>Цена:<\/strong> 1067,96 MONEY<\/p>/', $result);
        $this->assertRegExp('/<p><strong>Код:<\/strong> TEST<\/p>/', $result);
    }
}
