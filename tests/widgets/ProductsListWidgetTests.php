<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ProductsListWidget;
use app\collections\{BaseCollection,
    CollectionInterface,
    PaginationInterface};
use yii\db\Query;
use yii\base\{Model,
    Widget};
use yii\helpers\Html;

/**
 * Тестирует класс ProductsListWidget
 */
class ProductsListWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsListWidget::class);
        
        $this->assertTrue($reflection->hasProperty('productsCollection'));
        $this->assertTrue($reflection->hasProperty('priceWidget'));
        $this->assertTrue($reflection->hasProperty('thumbnailsWidget'));
        $this->assertTrue($reflection->hasProperty('paginationWidget'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод ProductsListWidget::setProductsCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProductsCollectionError()
    {
        $collection = new class() {};
        $widget = new ProductsListWidget();
        $widget->setProductsCollection($collection);
    }
    
    /**
     * Тестирует метод ProductsListWidget::setProductsCollection
     */
    public function testSetProductsCollection()
    {
        $productsCollection = new class() implements CollectionInterface {
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){}
            public function isEmpty(){}
            public function getModels(){}
            public function getArrays(){}
            public function setPagination(PaginationInterface $pagination){}
            public function getPagination(){}
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
        };
        
        $widget = new ProductsListWidget();
        $widget->setProductsCollection($productsCollection);
        
        $reflection = new \ReflectionProperty($widget, 'productsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод ProductsListWidget::setPriceWidget
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPriceWidgetError()
    {
        $priceWidget = new class() {};
        $widget = new ProductsListWidget();
        $widget->setPriceWidget($priceWidget);
    }
    
    /**
     * Тестирует метод ProductsListWidget::setPriceWidget
     */
    public function testSetPriceWidget()
    {
        $priceWidget = new class() extends Widget {};
        $widget = new ProductsListWidget();
        $widget->setPriceWidget($priceWidget);
        
        $reflection = new \ReflectionProperty($widget, 'priceWidget');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Widget::class, $result);
    }
    
    /**
     * Тестирует метод ProductsListWidget::setThumbnailsWidget
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetThumbnailsWidgetError()
    {
        $thumbnailsWidget = new class() {};
        $widget = new ProductsListWidget();
        $widget->setThumbnailsWidget($thumbnailsWidget);
    }
    
    /**
     * Тестирует метод ProductsListWidget::setThumbnailsWidget
     */
    public function testSetThumbnailsWidget()
    {
        $thumbnailsWidget = new class() extends Widget {};
        $widget = new ProductsListWidget();
        $widget->setThumbnailsWidget($thumbnailsWidget);
        
        $reflection = new \ReflectionProperty($widget, 'thumbnailsWidget');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Widget::class, $result);
    }
    
    /**
     * Тестирует метод ProductsListWidget::setPaginationWidget
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPaginationWidgetError()
    {
        $paginationWidget = new class() {};
        $widget = new ProductsListWidget();
        $widget->setPaginationWidget($paginationWidget);
    }
    
    /**
     * Тестирует метод ProductsListWidget::setPaginationWidget
     */
    public function testSetPaginationWidget()
    {
        $paginationWidget = new class() extends Widget {};
        $widget = new ProductsListWidget();
        $widget->setPaginationWidget($paginationWidget);
        
        $reflection = new \ReflectionProperty($widget, 'paginationWidget');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Widget::class, $result);
    }
    
     /**
     * Тестирует метод ProductsListWidget::run
     * при отсутствии ProductsListWidget::productsCollection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: productsCollection
     */
    public function testRunEmptyProductsCollection()
    {
        $widget = new ProductsListWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductsListWidget::run
     * при отсутствии ProductsListWidget::priceWidget
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: priceWidget
     */
    public function testRunEmptyPriceWidget()
    {
        $productsCollection = new class() implements CollectionInterface {
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){}
            public function isEmpty(){}
            public function getModels(){}
            public function getArrays(){}
            public function setPagination(PaginationInterface $pagination){}
            public function getPagination(){}
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
        };
        
        $widget = new ProductsListWidget();
        
        $reflection = new \ReflectionProperty($widget, 'productsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $productsCollection);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductsListWidget::run
     * при отсутствии ProductsListWidget::thumbnailsWidget
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: thumbnailsWidget
     */
    public function testRunEmptyThumbnailsWidget()
    {
        $productsCollection = new class() implements CollectionInterface {
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){}
            public function isEmpty(){}
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
        
        $widget = new ProductsListWidget();
        
        $reflection = new \ReflectionProperty($widget, 'productsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $productsCollection);
        
        $reflection = new \ReflectionProperty($widget, 'priceWidget');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $priceWidget);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductsListWidget::run
     * при отсутствии ProductsListWidget::paginationWidget
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: paginationWidget
     */
    public function testRunEmptyPaginationWidget()
    {
        $productsCollection = new class() implements CollectionInterface {
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){}
            public function isEmpty(){}
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
        $thumbnailsWidget = new class() extends Widget {};
        
        $widget = new ProductsListWidget();
        
        $reflection = new \ReflectionProperty($widget, 'productsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $productsCollection);
        
        $reflection = new \ReflectionProperty($widget, 'priceWidget');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $priceWidget);
        
        $reflection = new \ReflectionProperty($widget, 'thumbnailsWidget');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $thumbnailsWidget);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductsListWidget::run
     * при отсутствии ProductsListWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $productsCollection = new class() implements CollectionInterface {
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){}
            public function isEmpty(){}
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
        $thumbnailsWidget = new class() extends Widget {};
        $paginationWidget = new class() extends Widget {};
        
        $widget = new ProductsListWidget();
        
        $reflection = new \ReflectionProperty($widget, 'productsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $productsCollection);
        
        $reflection = new \ReflectionProperty($widget, 'priceWidget');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $priceWidget);
        
        $reflection = new \ReflectionProperty($widget, 'thumbnailsWidget');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $thumbnailsWidget);
        
        $reflection = new \ReflectionProperty($widget, 'paginationWidget');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $paginationWidget);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ProductsListWidget::run
     */
    public function testRun()
    {
        $itemsArray = [
            new class() {
                public $name = 'Black mood shoes';
                public $seocode = 'black-mood-shoes';
                public $short_description = 'This Black mood shoes for crazy bunchers';
                public $price = 123.67;
                public $images = 'test';
            },
            new class() {
                public $name = 'Purple woman shirt';
                public $seocode = 'purple-woman-shirt';
                public $short_description = 'Nice shirt for nice women';
                public $price = 32.14;
                public $images = 'test';
            },
        ];
        
        $productsCollection = new class() extends BaseCollection implements CollectionInterface {
            protected $items;
            public function setQuery(Query $query){}
            public function getQuery(){}
            public function add(Model $object){}
            public function addArray(array $array){}
            public function isEmpty(){}
            public function getModels(){}
            public function getArrays(){}
            public function setPagination(PaginationInterface $pagination){}
            public function getPagination(){
                return new class() {};
            }
            public function map(string $key, string $value){}
            public function sort(string $key, $type){}
            public function hasEntity(Model $object){}
            public function update(Model $object){}
        };
        
        $reflection = new \ReflectionProperty($productsCollection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($productsCollection, $itemsArray);
        
        $priceWidget = new class() extends Widget {
            public $price;
            public function run()
            {
                return $this->price . ' MONEY';
            }
        };
        
        $thumbnailsWidget = new class() extends Widget {
            public $path;
            public function run()
            {
                $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $this->path) . '/thumbn_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                return Html::img(\Yii::getAlias('@imagesweb/' . $this->path . '/') . basename($imagesArray[random_int(0, count($imagesArray) - 1)]));
            }
        };
        
        $paginationWidget = new class() extends Widget {
            public $pagination;
            public function run()
            {
                return '<p>1 - 2 - 3</p>';
            }
        };
        
        $widget = new ProductsListWidget();
        
        $reflection = new \ReflectionProperty($widget, 'productsCollection');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $productsCollection);
        
        $reflection = new \ReflectionProperty($widget, 'priceWidget');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $priceWidget);
        
        $reflection = new \ReflectionProperty($widget, 'thumbnailsWidget');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $thumbnailsWidget);
        
        $reflection = new \ReflectionProperty($widget, 'paginationWidget');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $paginationWidget);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'products-list.twig');
        
        \Yii::$app->controller = new \app\controllers\ProductsListController('products-list', \Yii::$app);
        
        $result = $widget->run();
        
        $this->assertRegExp('/<a href=".+">Black mood shoes<\/a>/', $result);
        $this->assertRegExp('/<a href=".+">Purple woman shirt<\/a>/', $result);
        $this->assertRegExp('/This Black mood shoes for crazy bunchers/', $result);
        $this->assertRegExp('/Nice shirt for nice women/', $result);
        $this->assertRegExp('/' . \Yii::t('base', 'Price:') . ' 123.67 MONEY/', $result);
        $this->assertRegExp('/' . \Yii::t('base', 'Price:') . ' 32.14 MONEY/', $result);
        $this->assertRegExp('/<img src=".+" alt="">/', $result);
        $this->assertRegExp('/<ol>/', $result);
        $this->assertRegExp('/<li>/', $result);
    }
}
