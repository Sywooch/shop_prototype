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
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategoriesMenuWidget::class);
        
        $this->assertTrue($reflection->hasProperty('categories'));
        $this->assertTrue($reflection->hasProperty('rootRoute'));
        $this->assertTrue($reflection->hasProperty('activateParents'));
        $this->assertTrue($reflection->hasProperty('submenuTemplate'));
        $this->assertTrue($reflection->hasProperty('options'));
    }
    
    /**
     * Тестирует метод CategoriesMenuWidget::setCategories
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCategoriesError()
    {
        $categories = new class() {};
        
        $widget = new CategoriesMenuWidget(['categories'=>$categories]);
    }
    
    /**
     * Тестирует метод CategoriesMenuWidget::setCategories
     */
    public function testSetCategories()
    {
        $widget = new CategoriesMenuWidget(['categories'=>[new class() {}]]);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод CategoriesMenuWidget::setItems
     * если пуст CategoriesMenuWidget::categories
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: categories
     */
    public function testSetItemsEmptyCategories()
    {
        $widget = new CategoriesMenuWidget(['categories'=>[]]);
        
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
        
        $category_1 = new class() {
            public $active = true;
            public $name = 'Mens footwear';
            public $seocode = 'mens-footwear';
            public $subcategory;
        };
        
        $reflection = new \ReflectionProperty($category_1, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($category_1, [$subcategory_1]);
        
        $subcategory_2 = new class() {
            public $active = true;
            public $name = 'Coat';
            public $seocode = 'coat';
        };
        
        $category_2 = new class() {
            public $active = true;
            public $name = 'mens-clothes';
            public $seocode = 'mens-clothes';
            public $subcategory;
        };
        
        $reflection = new \ReflectionProperty($category_2, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($category_2, [$subcategory_2]);
        
        $widget = new CategoriesMenuWidget(['categories'=>[$category_1, $category_2]]);
        
        $reflection = new \ReflectionProperty($widget, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
    }
}
