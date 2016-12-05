<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CategoriesMenuWidget;
use app\collections\{CollectionInterface,
    PaginationInterface};
use yii\db\Query;
use yii\base\Model;

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
        $widget = new CategoriesMenuWidget();
        $widget->setCategoriesCollection($categoriesCollection);
    }
    
    /**
     * Тестирует метод CategoriesMenuWidget::setCategoriesCollection
     */
    public function testSetCategoriesCollection()
    {
        $categoriesCollection = new class() implements CollectionInterface {
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
        
        $widget = new CategoriesMenuWidget();
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
        $widget = new CategoriesMenuWidget();
        
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
        
        $categoriesCollection = new class() implements CollectionInterface {
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
        
        $widget = new CategoriesMenuWidget();
        
        $reflection = new \ReflectionMethod($widget, 'setItems');
        $reflection->setAccessible(true);
        $reflection->invoke($widget);
    }
}
