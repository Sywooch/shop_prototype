<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\FiltersWidget;
use app\collections\{SortingFieldsCollection,
    SortingTypesCollection};
use app\forms\FiltersForm;

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
        
        $this->assertTrue($reflection->hasProperty('colors'));
        $this->assertTrue($reflection->hasProperty('sizes'));
        $this->assertTrue($reflection->hasProperty('brands'));
        $this->assertTrue($reflection->hasProperty('sortingFields'));
        $this->assertTrue($reflection->hasProperty('sortingTypes'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод FiltersWidget::setColors
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetColorsError()
    {
        $colors = new class() {};
        
        $widget = new FiltersWidget();
        $widget->setColors($colors);
    }
    
    /**
     * Тестирует метод FiltersWidget::setColors
     */
    public function testSetColors()
    {
        $colors = [1=>'black'];
        
        $widget = new FiltersWidget();
        $widget->setColors($colors);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод FiltersWidget::setSizes
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSizesError()
    {
        $sizes = new class() {};
        
        $widget = new FiltersWidget();
        $widget->setSizes($sizes);
    }
    
    /**
     * Тестирует метод FiltersWidget::setSizes
     */
    public function testSetSizes()
    {
        $sizes = [1=>45.5];
        
        $widget = new FiltersWidget();
        $widget->setSizes($sizes);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод FiltersWidget::setBrands
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetBrandsError()
    {
        $brands = new class() {};
        
        $widget = new FiltersWidget();
        $widget->setBrands($brands);
    }
    
    /**
     * Тестирует метод FiltersWidget::setBrands
     */
    public function testSetBrands()
    {
        $brands = [1=>'Puma'];
        
        $widget = new FiltersWidget();
        $widget->setBrands($brands);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод FiltersWidget::setSortingFields
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSortingFieldsError()
    {
        $collection = new class() {};
        
        $widget = new FiltersWidget();
        $widget->setSortingFields($collection);
    }
    
    /**
     * Тестирует метод FiltersWidget::setSortingFields
     */
    public function testSetSortingFields()
    {
        $collection = ['price'=>'Price'];
        
        $widget = new FiltersWidget();
        $widget->setSortingFields($collection);
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод FiltersWidget::setSortingTypes
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSortingTypesError()
    {
        $collection = new class() {};
        
        $widget = new FiltersWidget();
        $widget->setSortingTypes($collection);
    }
    
    /**
     * Тестирует метод FiltersWidget::setSortingTypes
     */
    public function testSetSortingTypes()
    {
        $collection = [SORT_ASC=>'asc'];
        
        $widget = new FiltersWidget();
        $widget->setSortingTypes($collection);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод FiltersWidget::setForm
     * передаю параметр неверного типа
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
        $form = new class() extends FiltersForm {};
        
        $widget = new FiltersWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(FiltersForm::class, $result);
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::colors
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: colors
     */
    public function testRunEmptyColors()
    {
        $widget = new FiltersWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::sizes
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sizes
     */
    public function testRunEmptySizes()
    {
        $data = [1=>'some'];
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::brands
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: brands
     */
    public function testRunEmptyBrands()
    {
        $data = [1=>'some'];
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::sortingFields
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sortingFields
     */
    public function testRunEmptySortingFields()
    {
        $data = [1=>'some'];
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::sortingTypes
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sortingTypes
     */
    public function testRunEmptySortingTypes()
    {
        $data = [1=>'some'];
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $data = [1=>'some'];
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     * если пуст FiltersWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $data = [1=>'some'];
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$data]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод FiltersWidget::run
     */
    public function testRun()
    {
        $colors = [1=>'black', 2=>'green'];
        
        $sizes = [1=>45, 2=>35.5];
        
        $brands = [1=>'Adidas', 2=>'Nordic Blast'];
        
        $sortingFields = ['date'=>'Sorting by date', 'price'=>'Sorting by price'];
        
        $sortingTypes = [SORT_ASC=>'Sort ascending', SORT_DESC=>'Sort descending'];
        
        $form = new class() extends FiltersForm {
            public $sortingField;
            public $sortingType;
            public $colors;
            public $sizes;
            public $brands;
            public $url;
        };
        
        $widget = new FiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $colors);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $sizes);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $brands);
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $sortingFields);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $sortingTypes);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'products-filters.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Фильтры</strong></p>#', $result);
        $this->assertRegExp('#<form id="products-filters-form"#', $result);
        $this->assertRegExp('#<option value="date">Sorting by date</option>#', $result);
        $this->assertRegExp('#<option value="price">Sorting by price</option>#', $result);
        $this->assertRegExp('#<option value="4">Sort ascending</option>#', $result);
        $this->assertRegExp('#<option value="3">Sort descending</option>#', $result);
        $this->assertRegExp('#<label class="control-label">Colors</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+" value="1"> black</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+" value="2"> green</label>#', $result);
        $this->assertRegExp('#<label class="control-label">Sizes</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+" value="1"> 45</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+" value="2"> 35.5</label>#', $result);
        $this->assertRegExp('#<label class="control-label">Brands</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+" value="1"> Adidas</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+" value="2"> Nordic Blast</label>#', $result);
        $this->assertRegExp('#<input type="submit" value="Применить">#', $result);
        $this->assertRegExp('#<form id="products-filters-clean"#', $result);
        $this->assertRegExp('#<input type="submit" value="Очистить">#', $result);
    }
}
