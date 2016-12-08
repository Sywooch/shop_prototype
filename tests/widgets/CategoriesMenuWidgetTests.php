<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CategoriesMenuWidget;
use app\collections\{BaseCollection,
    CollectionInterface};

/**
 * Тестирует класс CategoriesMenuWidget
 */
class CategoriesMenuWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new class() extends CategoriesMenuWidget {
            public function init() {}
        };
    }
    
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategoriesMenuWidget::class);
        
        $this->assertTrue($reflection->hasProperty('categoriesCollection'));
        $this->assertTrue($reflection->hasProperty('rootRoute'));
        $this->assertTrue($reflection->hasProperty('activateParents'));
        $this->assertTrue($reflection->hasProperty('submenuTemplate'));
        $this->assertTrue($reflection->hasProperty('options'));
    }
    
    /**
     * Тестирует метод CategoriesMenuWidget::setCategoriesCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCategoriesCollectionError()
    {
        $categoriesCollection = new class() {};
        $widget = new $this->widget();
        $widget->setCategoriesCollection($categoriesCollection);
    }
    
    /**
     * Тестирует метод CategoriesMenuWidget::setCategoriesCollection
     */
    public function testSetCategoriesCollection()
    {
        $categoriesCollection = new class() extends BaseCollection {};
        
        $widget = new $this->widget();
        $widget->setCategoriesCollection($categoriesCollection);
        
        $reflection = new \ReflectionProperty($widget, 'categoriesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод CategoriesMenuWidget::setItems
     * при отсутствии CategoriesMenuWidget::categoriesCollection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: categoriesCollection
     */
    public function testSetItemsEmptyCategoriesCollection()
    {
        $widget = new $this->widget();
        
        $reflection = new \ReflectionMethod($widget, 'setItems');
        $reflection->setAccessible(true);
        $reflection->invoke($widget);
    }
    
    /**
     * Тестирует метод CategoriesMenuWidget::setItems
     */
    public function testSetItems()
    {
        $subcategory_1 = new class() {
            public $active = true;
            public $name = 'Shoes';
            public $seocode = 'shoes';
        };
        $subcategory_2 = new class() {
            public $active = true;
            public $name = 'Coat';
            public $seocode = 'coat';
        };
        
        $category_1 = new class() {
            public $active = true;
            public $name = 'Mens footwear';
            public $seocode = 'mens-footwear';
            public $subcategory;
        };
        
        $reflection = new \ReflectionProperty($category_1, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($category_1, [$subcategory_1]);
        
        $category_2 = new class() {
            public $active = true;
            public $name = 'mens-clothes';
            public $seocode = 'mens-clothes';
            public $subcategory;
        };
        
        $reflection = new \ReflectionProperty($category_2, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($category_2, [$subcategory_2]);
        
        $categoriesCollection = new class() extends BaseCollection {};
        
        $reflection = new \ReflectionProperty($categoriesCollection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($categoriesCollection, [$category_1, $category_2]);
        
        $widget = new $this->widget();
        
        $reflection = new \ReflectionProperty($widget, 'categoriesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $categoriesCollection);
        
        $reflection = new \ReflectionMethod($widget, 'setItems');
        $reflection->setAccessible(true);
        $reflection->invoke($widget);
        
        $this->assertNotEmpty($widget->items);
        $this->assertCount(2, $widget->items);
    }
}
