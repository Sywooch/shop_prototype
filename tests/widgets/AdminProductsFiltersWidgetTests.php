<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminProductsFiltersWidget;
use app\collections\{SortingFieldsCollection,
    SortingTypesCollection};
use app\forms\AdminProductsFiltersForm;
use app\controllers\AdminController;

/**
 * Тестирует класс AdminProductsFiltersWidget
 */
class AdminProductsFiltersWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
        
        $this->widget = new AdminProductsFiltersWidget();
    }
    
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductsFiltersWidget::class);
        
        $this->assertTrue($reflection->hasProperty('sortingFields'));
        $this->assertTrue($reflection->hasProperty('sortingTypes'));
        $this->assertTrue($reflection->hasProperty('colors'));
        $this->assertTrue($reflection->hasProperty('sizes'));
        $this->assertTrue($reflection->hasProperty('brands'));
        $this->assertTrue($reflection->hasProperty('categories'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
        $this->assertTrue($reflection->hasProperty('activeStatuses'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setSortingFields
     */
    public function testSetSortingFields()
    {
        $sortingFields = [null];
        
        $this->widget->setSortingFields($sortingFields);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setSortingTypes
     */
    public function testSetSortingTypes()
    {
        $sortingTypes = [null];
        
        $this->widget->setSortingTypes($sortingTypes);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setColors
     */
    public function testSetColors()
    {
        $colors = [null];
        
        $this->widget->setColors($colors);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setSizes
     */
    public function testSetSizes()
    {
        $sizes = [null];
        
        $this->widget->setSizes($sizes);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setBrands
     */
    public function testSetBrands()
    {
        $brands = [null];
        
        $this->widget->setBrands($brands);
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setCategories
     */
    public function testSetCategories()
    {
        $categories = [null];
        
        $this->widget->setCategories($categories);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setSubcategory
     */
    public function testSetSubcategory()
    {
        $subcategory = [null];
        
        $this->widget->setSubcategory($subcategory);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setActiveStatuses
     */
    public function testSetActiveStatuses()
    {
        $activeStatuses = [null];
        
        $this->widget->setActiveStatuses($activeStatuses);
        
        $reflection = new \ReflectionProperty($this->widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends AdminProductsFiltersForm {};
        
        $this->widget->setForm($form);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AdminProductsFiltersForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $this->widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setTemplate
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
     * Тестирует метод AdminProductsFiltersWidget::run
     * если пуст AdminProductsFiltersWidget::sortingFields
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sortingFields
     */
    public function testRunEmptySortingFields()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::run
     * если пуст AdminProductsFiltersWidget::sortingTypes
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sortingTypes
     */
    public function testRunEmptySortingTypes()
    {
        $mock = 'mock';
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::run
     * если пуст AdminProductsFiltersWidget::colors
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: colors
     */
    public function testRunEmptyColors()
    {
        $mock = 'mock';
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::run
     * если пуст AdminProductsFiltersWidget::sizes
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sizes
     */
    public function testRunEmptySizes()
    {
        $mock = 'mock';
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::run
     * если пуст AdminProductsFiltersWidget::brands
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: brands
     */
    public function testRunEmptyBrands()
    {
        $mock = 'mock';
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::run
     * если пуст AdminProductsFiltersWidget::categories
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: categories
     */
    public function testRunEmptyCategories()
    {
        $mock = 'mock';
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::run
     * если пуст AdminProductsFiltersWidget::subcategory
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: subcategory
     */
    public function testRunEmptySubcategory()
    {
        $mock = 'mock';
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::run
     * если пуст AdminProductsFiltersWidget::activeStatuses
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: activeStatuses
     */
    public function testRunEmptyActiveStatuses()
    {
        $mock = 'mock';
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::run
     * если пуст AdminProductsFiltersWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = 'mock';
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::run
     * если пуст AdminProductsFiltersWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = 'mock';
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::run
     * если пуст AdminProductsFiltersWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = 'mock';
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::run
     */
    public function testRun()
    {
        $sortingFields = ['date'=>'Date', 'price'=>'Price'];
        $sortingTypes = [SORT_ASC=>'Sort ascending', SORT_DESC=>'Sort descending'];
        $colors = [1=>'black', 2=>'red'];
        $sizes = [0=>35, 2=>45];
        $brands = [1=>'Adidas', 3=>'Canon'];
        $categories = [0=>\Yii::$app->params['formFiller'], 1=>'Shoes', 2=>'Hats'];
        $subcategory = [0=>\Yii::$app->params['formFiller'], 1=>'Sneakers'];
        $activeStatuses = [1=>'Active', 0=>'Not active'];
        $form = new class() extends AdminProductsFiltersForm {};
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $sortingFields);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $sortingTypes);
        
        $reflection = new \ReflectionProperty($this->widget, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $colors);
        
        $reflection = new \ReflectionProperty($this->widget, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $sizes);
        
        $reflection = new \ReflectionProperty($this->widget, 'brands');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $brands);
        
        $reflection = new \ReflectionProperty($this->widget, 'categories');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $categories);
        
        $reflection = new \ReflectionProperty($this->widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $subcategory);
        
        $reflection = new \ReflectionProperty($this->widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $activeStatuses);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, 'admin-products-filters.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<div class="products-filters">#', $result);
        $this->assertRegExp('#<form id="admin-products-filters-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[sortingField\]">#', $result);
        $this->assertRegExp('#<option value="date">Date</option>#', $result);
        $this->assertRegExp('#<option value="price">Price</option>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[sortingType\]">#', $result);
        $this->assertRegExp('#<option value="4">Sort ascending</option>#', $result);
        $this->assertRegExp('#<option value="3">Sort descending</option>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[colors\]\[\]" value="1"> black</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[colors\]\[\]" value="2"> red</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[sizes\]\[\]" value="0"> 35</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[sizes\]\[\]" value="2"> 45</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[brands\]\[\]" value="1"> Adidas</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[brands\]\[\]" value="3"> Canon</label>#', $result);
        $this->assertRegExp('#<option value="0">------------------------</option>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[category\]" data-href=".+">#', $result);
        $this->assertRegExp('#<option value="1">Shoes</option>#', $result);
        $this->assertRegExp('#<option value="2">Hats</option>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[subcategory\]">#', $result);
        $this->assertRegExp('#<option value="1">Sneakers</option>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[active\]">#', $result);
        $this->assertRegExp('#<option value="1">Active</option>#', $result);
        $this->assertRegExp('#<option value="0">Not active</option>#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[url\]" value=".+">#', $result);
        $this->assertRegExp('#<input type="submit" value="Применить">#', $result);
        $this->assertRegExp('#<form id="admin-products-filters-clean" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="submit" value="Очистить">#', $result);
    }
}
