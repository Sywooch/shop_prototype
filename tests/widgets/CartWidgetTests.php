<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CartWidget;
use app\collections\{CollectionInterface,
    PaginationInterface};
use yii\db\Query;
use yii\base\{Model,
    Widget};

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
        
        $this->assertTrue($reflection->hasProperty('purchasesCollection'));
        $this->assertTrue($reflection->hasProperty('priceWidget'));
        $this->assertTrue($reflection->hasProperty('view'));
        $this->assertTrue($reflection->hasProperty('goods'));
        $this->assertTrue($reflection->hasProperty('cost'));
    }
    
    /**
     * Тестирует метод CartWidget::setPurchasesCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchasesCollectionError()
    {
        $purchasesCollection = new class() {};
        $widget = new CartWidget();
        $widget->setPurchasesCollection($purchasesCollection);
    }
    
    /**
     * Тестирует метод CartWidget::setPurchasesCollection
     */
    public function testSetPurchasesCollection()
    {
        $purchasesCollection = new class() implements CollectionInterface {
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){}
            public function isEmpty(){}
            public function isArrays(){}
            public function getModels(){}
            public function getArrays(){}
            public function setPagination(PaginationInterface $pagination){}
            public function getPagination(){}
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
        };
        
        $widget = new CartWidget();
        $widget->setPurchasesCollection($purchasesCollection);
        
        $reflection = new \ReflectionProperty($widget, 'purchasesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод CartWidget::setPriceWidget
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPriceWidgetError()
    {
        $priceWidget = new class() {};
        $widget = new CartWidget();
        $widget->setPriceWidget($priceWidget);
    }
    
    /**
     * Тестирует метод CartWidget::setPriceWidget
     */
    public function testSetPriceWidget()
    {
        $priceWidget = new class() extends Widget {};
        $widget = new CartWidget();
        $widget->setPriceWidget($priceWidget);
        
        $reflection = new \ReflectionProperty($widget, 'priceWidget');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Widget::class, $result);
    }
    
    /**
     * Тестирует метод CartWidget::run
     * при отсутствии CartWidget::purchasesCollection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: purchasesCollection
     */
    public function testRunEmptyPurchasesCollection()
    {
        $widget = new CartWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CartWidget::run
     * при отсутствии CartWidget::priceWidget
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: priceWidget
     */
    public function testRunEmptyPriceWidget()
    {
        $purchasesCollection = new class() implements CollectionInterface {
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){}
            public function isEmpty(){}
            public function isArrays(){}
            public function getModels(){}
            public function getArrays(){}
            public function setPagination(PaginationInterface $pagination){}
            public function getPagination(){}
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
        };
        
        $widget = new CartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchasesCollection');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchasesCollection);
        
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
        $purchasesCollection = new class() implements CollectionInterface {
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){}
            public function isEmpty(){}
            public function isArrays(){}
            public function getModels(){}
            public function getArrays(){}
            public function setPagination(PaginationInterface $pagination){}
            public function getPagination(){}
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
        };
        
        $priceWidget = new class() extends Widget {};
        
        $widget = new CartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchasesCollection');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchasesCollection);
        
        $reflection = new \ReflectionProperty($widget, 'priceWidget');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $priceWidget);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CartWidget::run
     */
    public function testRun()
    {
        $purchasesCollection = new class() implements CollectionInterface {
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){}
            public function isEmpty(){
                return false;
            }
            public function isArrays(){}
            public function getModels(){}
            public function getArrays(){}
            public function setPagination(PaginationInterface $pagination){}
            public function getPagination(){}
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
            public function totalQuantity() {
                return 14;
            }
            public function totalPrice() {
                return 6895.42;
            }
        };
        
        $priceWidget = new class() extends Widget {
            public $price;
            public function run()
            {
                return $this->price . ' MONEY';
            }
        };
        
        $widget = new CartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchasesCollection');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchasesCollection);
        
        $reflection = new \ReflectionProperty($widget, 'priceWidget');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $priceWidget);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'short-cart.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('/<div id="cart">/', $result);
        $this->assertRegExp('/' . \Yii::t('base', 'Products in cart: {goods}, Total cost: {cost}', ['goods'=>14, 'cost'=>6895.42]) . ' MONEY/', $result);
        $this->assertRegExp('/<a href=".+">' . \Yii::t('base', 'To cart') . '<\/a>/', $result);
        $this->assertRegExp('/<form id="clean-cart-form"/', $result);
        $this->assertRegExp('/<input type="submit" value="' . \Yii::t('base', 'Clean' ) . '">/', $result);
    }
}
