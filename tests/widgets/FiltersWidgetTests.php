<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\FiltersWidget;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\base\Model;

/**
 * Тестирует класс FiltersWidget
 */
class FiltersWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(FiltersWidget::class);
        
        $this->assertTrue($reflection->hasProperty('colorsCollection'));
        $this->assertTrue($reflection->hasProperty('sizesCollection'));
        $this->assertTrue($reflection->hasProperty('brandsCollection'));
        $this->assertTrue($reflection->hasProperty('sortingFieldsCollection'));
        $this->assertTrue($reflection->hasProperty('sortingTypesCollection'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод FiltersWidget::setColorsCollection
     * если передаю неверный тип аргумента
     * @expectedException TypeError
     */
    public function testSetColorsCollectionError()
    {
        $collection = new class() {};
        $widget = new FiltersWidget();
        $widget->setColorsCollection($collection);
    }
    
    /**
     * Тестирует метод FiltersWidget::setColorsCollection
     */
    public function testSetColorsCollection()
    {
        $collection = new class() extends BaseCollection {};
        $widget = new FiltersWidget();
        $widget->setColorsCollection($collection);
        
        $reflection = new \ReflectionProperty($widget, 'colorsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод FiltersWidget::setSizesCollection
     * если передаю неверный тип аргумента
     * @expectedException TypeError
     */
    public function testSetSizesCollectionError()
    {
        $collection = new class() {};
        $widget = new FiltersWidget();
        $widget->setSizesCollection($collection);
    }
    
    /**
     * Тестирует метод FiltersWidget::setSizesCollection
     */
    public function testSetSizesCollection()
    {
        $collection = new class() extends BaseCollection {};
        $widget = new FiltersWidget();
        $widget->setSizesCollection($collection);
        
        $reflection = new \ReflectionProperty($widget, 'sizesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод FiltersWidget::setBrandsCollection
     * если передаю неверный тип аргумента
     * @expectedException TypeError
     */
    public function testSetBrandsCollectionError()
    {
        $collection = new class() {};
        $widget = new FiltersWidget();
        $widget->setBrandsCollection($collection);
    }
    
    /**
     * Тестирует метод FiltersWidget::setBrandsCollection
     */
    public function testSetBrandsCollection()
    {
        $collection = new class() extends BaseCollection {};
        $widget = new FiltersWidget();
        $widget->setBrandsCollection($collection);
        
        $reflection = new \ReflectionProperty($widget, 'brandsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод FiltersWidget::setSortingFieldsCollection
     * если передаю неверный тип аргумента
     * @expectedException TypeError
     */
    public function testSetSortingFieldsCollectionError()
    {
        $collection = new class() {};
        $widget = new FiltersWidget();
        $widget->setSortingFieldsCollection($collection);
    }
    
    /**
     * Тестирует метод FiltersWidget::setSortingFieldsCollection
     */
    public function testSetSortingFieldsCollection()
    {
        $collection = new class() extends BaseCollection {};
        
        $widget = new FiltersWidget();
        $widget->setSortingFieldsCollection($collection);
        
        $reflection = new \ReflectionProperty($widget, 'sortingFieldsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод FiltersWidget::setSortingTypesCollection
     * если передаю неверный тип аргумента
     * @expectedException TypeError
     */
    public function testSetSortingTypesCollectionError()
    {
        $collection = new class() {};
        $widget = new FiltersWidget();
        $widget->setSortingTypesCollection($collection);
    }
    
    /**
     * Тестирует метод FiltersWidget::setSortingTypesCollection
     */
    public function testSetSortingTypesCollection()
    {
        $collection = new class() extends BaseCollection {};
        
        $widget = new FiltersWidget();
        $widget->setSortingTypesCollection($collection);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод FiltersWidget::setForm
     * если передаю неверный тип аргумента
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        $widget = new FiltersWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод FiltersWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends Model {};
        $widget = new FiltersWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::colorsCollection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: colorsCollection
     */
    public function testRunEmptyColorsCollection()
    {
        $widget = new FiltersWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::sizesCollection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: sizesCollection
     */
    public function testRunEmptySizesCollection()
    {
        $collection = new class() extends BaseCollection {};
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colorsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::brandsCollection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: brandsCollection
     */
    public function testRunEmptyBrandsCollection()
    {
        $collection = new class() extends BaseCollection {};
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colorsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'sizesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::sortingFieldsCollection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: sortingFieldsCollection
     */
    public function testRunEmptySortingFieldsCollection()
    {
        $collection = new class() extends BaseCollection {};
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colorsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'sizesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'brandsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::sortingTypesCollection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: sortingTypesCollection
     */
    public function testRunEmptySortingTypesCollection()
    {
        $collection = new class() extends BaseCollection {};
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colorsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'sizesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'brandsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'sortingFieldsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: form
     */
    public function testRunEmptyForm()
    {
        $collection = new class() extends BaseCollection {};
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colorsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'sizesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'brandsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'sortingFieldsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $collection = new class() extends BaseCollection {};
        $form = new class() extends Model {};
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colorsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'sizesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'brandsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'sortingFieldsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $collection);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     */
    public function testRun()
    {
        $colorsCollection = new class() extends BaseCollection {
            public $items = [
                ['id'=>1, 'color'=>'black'],
                ['id'=>2, 'color'=>'green'],
            ];
        };
        
        $sizesCollection = new class() extends BaseCollection {
            public $items = [
                ['id'=>1, 'size'=>45],
                ['id'=>2, 'size'=>35.5],
            ];
        };
        
        $brandsCollection = new class() extends BaseCollection {
            public $items = [
                ['id'=>1, 'brand'=>'Adidas'],
                ['id'=>2, 'brand'=>'Nordic Blast'],
            ];
        };
        
        $sortingFieldsCollection = new class() extends BaseCollection {
            public $items = [
                ['name'=>'date', 'value'=>'Sorting by date'],
                ['name'=>'price', 'value'=>'Sorting by price'],
            ];
        };
        
        $sortingTypesCollection = new class() extends BaseCollection {
            public $items = [
                ['name'=>'SORT_ASC', 'value'=>'Sort ascending'],
                ['name'=>'SORT_DESC', 'value'=>'Sort descending'],
            ];
        };
        
        $form = new class() extends Model {
            public $sortingField;
            public $sortingType;
            public $colors;
            public $sizes;
            public $brands;
            public $url;
        };
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colorsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $colorsCollection);
        
        $reflection = new \ReflectionProperty($widget, 'sizesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $sizesCollection);
        
        $reflection = new \ReflectionProperty($widget, 'brandsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $brandsCollection);
        
        $reflection = new \ReflectionProperty($widget, 'sortingFieldsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $sortingFieldsCollection);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypesCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $sortingTypesCollection);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'products-filters.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('/<p><strong>' . \Yii::t('base', 'Filters') . '<\/strong><\/p>/', $result);
        $this->assertRegExp('/<form id="products-filters-form"/', $result);
        $this->assertRegExp('/<option value="date">Sorting by date<\/option>/', $result);
        $this->assertRegExp('/<option value="price">Sorting by price<\/option>/', $result);
        $this->assertRegExp('/<option value="SORT_ASC">Sort ascending<\/option>/', $result);
        $this->assertRegExp('/<option value="SORT_DESC">Sort descending<\/option>/', $result);
        $this->assertRegExp('/<label class="control-label">' . \Yii::t('base', 'Colors') . '<\/label>/', $result);
        $this->assertRegExp('/<label class="control-label">' . \Yii::t('base', 'Sizes') . '<\/label>/', $result);
        $this->assertRegExp('/<label class="control-label">' . \Yii::t('base', 'Brands') . '<\/label>/', $result);
        $this->assertRegExp('/<input type="submit" value="' . \Yii::t('base', 'Apply') . '">/', $result);
        $this->assertRegExp('/<form id="products-filters-clean"/', $result);
        $this->assertRegExp('/<input type="submit" value="' . \Yii::t('base', 'Clean') . '">/', $result);
    }
}
