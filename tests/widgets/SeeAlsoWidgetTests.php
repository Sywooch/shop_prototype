<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\SeeAlsoWidget;
use app\models\CurrencyModel;

/**
 * Тестирует класс SeeAlsoWidget
 */
class SeeAlsoWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств SeeAlsoWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SeeAlsoWidget::class);
        
        $this->assertTrue($reflection->hasProperty('products'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setProducts
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProducstError()
    {
        $products = new class() {};
        
        $widget = new SeeAlsoWidget();
        $widget->setProducts($products);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setProducts
     */
    public function testSetProducts()
    {
        $products = [new class() {}];
        
        $widget = new SeeAlsoWidget();
        $widget->setProducts($products);
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setCurrency
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new SeeAlsoWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new SeeAlsoWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::run
     */
    public function testRunEmptyProducts()
    {
        $widget = new SeeAlsoWidget();
        $result = $widget->run();
        
        $this->assertSame('', $result);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::run
     * если пуст SeeAlsoWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $products = [new class() {}];
        
        $widget = new SeeAlsoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $products);
        
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::run
     * если пуст SeeAlsoWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $products = [new class() {}];
        $currency = new class() extends CurrencyModel {};
        
        $widget = new SeeAlsoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $products);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::run
     * если пуст SeeAlsoWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $products = [new class() {}];
        $currency = new class() extends CurrencyModel {};
        
        $widget = new SeeAlsoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $products);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::run
     */
    public function testRun()
    {
        $product_1 = new class() {
            public $name = 'One';
            public $seocode = 'one';
            public $images = 'test';
            public $price = 135;
        };
        
        $product_2 = new class() {
            public $name = 'Two';
            public $seocode = 'two';
            public $images = 'test';
            public $price = 98.56;
        };
        
        $currency = new class() {
            public $exchange_rate = 2.4587;
            public $code = 'MONEY';
        };
        
        $widget = new SeeAlsoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$product_1, $product_2]);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'see-also.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<a href="../vendor/phpunit/phpunit/one">One</a>#', $result);
        $this->assertRegExp('#<img src=".+" height="150" alt="">#', $result);
        $this->assertRegExp('#Цена: 331,92 MONEY#', $result);
        $this->assertRegExp('#<a href="../vendor/phpunit/phpunit/two">Two</a>#', $result);
        $this->assertRegExp('#<img src=".+" height="150" alt="">#', $result);
        $this->assertRegExp('#Цена: 242,33 MONEY#', $result);
    }
}
