<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ModFiltersWidget;
use app\forms\AbstractBaseForm;
use app\controllers\ProductsListController;

/**
 * Тестирует класс ModFiltersWidget
 */
class ModFiltersWidgetTests extends TestCase
{
    private $widget;
    
    public static function setUpBeforeClass()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
    }
    
    public function setUp()
    {
        $this->widget = new ModFiltersWidget();
    }
    
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ModFiltersWidget::class);
        
        $this->assertTrue($reflection->hasProperty('colors'));
        $this->assertTrue($reflection->hasProperty('sizes'));
        $this->assertTrue($reflection->hasProperty('sortingFields'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод ModFiltersWidget::setColors
     */
    public function testSetColors()
    {
        $colors = [1=>'black'];
        
        $this->widget->setColors($colors);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод ModFiltersWidget::setSizes
     */
    public function testSetSizes()
    {
        $sizes = [1=>45.5];
        
        $this->widget->setSizes($sizes);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод ModFiltersWidget::setSortingFields
     */
    public function testSetSortingFields()
    {
        $collection = ['price'=>'Price'];
        
        $this->widget->setSortingFields($collection);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод ModFiltersWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends AbstractBaseForm {};
        
        $this->widget->setForm($form);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод ModFiltersWidget::setTemplate
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
     * Тестирует метод ModFiltersWidget::run
     * если пуст ModFiltersWidget::colors
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: colors
     */
    public function testRunEmptyColors()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModFiltersWidget::run
     * если пуст ModFiltersWidget::sizes
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sizes
     */
    public function testRunEmptySizes()
    {
        $data = [1=>'some'];
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$data]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModFiltersWidget::run
     * если пуст ModFiltersWidget::sortingFields
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sortingFields
     */
    public function testRunEmptySortingFields()
    {
        $data = [1=>'some'];
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$data]);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$data]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModFiltersWidget::run
     * если пуст ModFiltersWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $data = [1=>'some'];
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$data]);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$data]);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$data]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModFiltersWidget::run
     * если пуст ModFiltersWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $data = [1=>'some'];
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$data]);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$data]);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$data]);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$data]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModFiltersWidget::run
     */
    public function testRun()
    {
        $colors = [
            new class() {
                public $id = 1;
                public $color = 'black';
                public $hexcolor = '#000000';
            },
            new class() {
                public $id = 2;
                public $color = 'gray';
                public $hexcolor = '#CCCCCC';
            },
        ];
        
        $sizes = [1=>45, 2=>35.5];
        
        $sortingFields = ['date descending'=>'Sorting by date', 'price ascending'=>'Sorting by price'];
        
        $form = new class() extends AbstractBaseForm {
            public $sortingField;
            public $colors;
            public $sizes;
            public $url;
            public $category;
            public $subcategory;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $colors);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $sizes);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $sortingFields);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, 'products-filters-mod.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<div id="products-filters">#', $result);
        $this->assertRegExp('#<ul class="products-filters-sorting-field" data-form-item="filtersform-sortingfield">#', $result);
        $this->assertRegExp('#<li><span class="products-filters-header first">Сортировка</span></li>#', $result);
        $this->assertRegExp('#<li data-id="date descending"><span class="products-filters-item">Sorting by date</span></li>#', $result);
        $this->assertRegExp('#<li data-id="price ascending"><span class="products-filters-item">Sorting by price</span></li>#', $result);
        $this->assertRegExp('#<ul class="products-filters-colors" data-form-item="filtersform-colors">#', $result);
        $this->assertRegExp('#<li><span class="products-filters-header">Цвета</span></li>#', $result);
        $this->assertRegExp('#<li data-id="1"><span class="color-hex" style="background-color:\#000000"></span><span class="products-filters-item">black</span></li>#', $result);
        $this->assertRegExp('#<ul class="products-filters-sizes" data-form-item="filtersform-sizes">#', $result);
        $this->assertRegExp('#<li><span class="products-filters-header">Размеры</span></li>#', $result);
        $this->assertRegExp('#<li data-id="1"><span class="products-filters-item">45</span></li>#', $result);
        $this->assertRegExp('#<ul class="products-filters-buttons">#', $result);
        $this->assertRegExp('#<li><span id="filters-apply" class="products-filters-button">Применить</span></li>#', $result);
        $this->assertRegExp('#<li><span id="filters-cancel" class="products-filters-button">Сбросить</span></li>#', $result);
        $this->assertRegExp('#<div class="products-filters-form disable">#', $result);
        $this->assertRegExp('#<form id="products-filters-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[sortingField\]">#', $result);
        $this->assertRegExp('#<option value="date descending">Sorting by date</option>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[colors\]\[\]" value="1"> black</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[sizes\]\[\]" value="1"> 45</label>#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[url\]" value=".+">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[category\]">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[subcategory\]">#', $result);
        $this->assertRegExp('#<form id="products-filters-clean" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[url\]" value=".+">#', $result);
    }
}
