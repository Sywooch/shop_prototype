<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminProductsFiltersWidget;
use app\collections\{SortingFieldsCollection,
    SortingTypesCollection};
use app\forms\AdminProductsFiltersForm;

/**
 * Тестирует класс AdminProductsFiltersWidget
 */
class AdminProductsFiltersWidgetTests extends TestCase
{
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
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSortingFieldsError()
    {
        $sortingFields = null;
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setSortingFields($sortingFields);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setSortingFields
     */
    public function testSetSortingFields()
    {
        $sortingFields = [null];
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setSortingFields($sortingFields);
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setSortingTypes
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSortingTypesError()
    {
        $sortingTypes = null;
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setSortingTypes($sortingTypes);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setSortingTypes
     */
    public function testSetSortingTypes()
    {
        $sortingTypes = [null];
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setSortingTypes($sortingTypes);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setColors
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetColorsError()
    {
        $colors = null;
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setColors($colors);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setColors
     */
    public function testSetColors()
    {
        $colors = [null];
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setColors($colors);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setSizes
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSizesError()
    {
        $sizes = null;
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setSizes($sizes);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setSizes
     */
    public function testSetSizes()
    {
        $sizes = [null];
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setSizes($sizes);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setBrands
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetBrandsError()
    {
        $brands = null;
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setBrands($brands);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setBrands
     */
    public function testSetBrands()
    {
        $brands = [null];
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setBrands($brands);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setCategories
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCategoriesError()
    {
        $categories = null;
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setCategories($categories);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setCategories
     */
    public function testSetCategories()
    {
        $categories = [null];
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setCategories($categories);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setSubcategory
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSubcategoryError()
    {
        $subcategory = null;
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setSubcategory($subcategory);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setSubcategory
     */
    public function testSetSubcategory()
    {
        $subcategory = [null];
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setSubcategory($subcategory);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setActiveStatuses
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetActiveStatusesError()
    {
        $activeStatuses = null;
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setActiveStatuses($activeStatuses);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setActiveStatuses
     */
    public function testSetActiveStatuses()
    {
        $activeStatuses = [null];
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setActiveStatuses($activeStatuses);
        
        $reflection = new \ReflectionProperty($widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends AdminProductsFiltersForm {};
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(AdminProductsFiltersForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AdminProductsFiltersWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AdminProductsFiltersWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
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
        $widget = new AdminProductsFiltersWidget();
        $widget->run();
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
        
        $widget = new AdminProductsFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
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
        
        $widget = new AdminProductsFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
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
        
        $widget = new AdminProductsFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
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
        
        $widget = new AdminProductsFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
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
        
        $widget = new AdminProductsFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
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
        
        $widget = new AdminProductsFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
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
        
        $widget = new AdminProductsFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
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
        
        $widget = new AdminProductsFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
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
        
        $widget = new AdminProductsFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
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
        
        $widget = new AdminProductsFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
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
        
        $widget = new AdminProductsFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $sortingFields);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $sortingTypes);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $colors);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $sizes);
        
        $reflection = new \ReflectionProperty($widget, 'brands');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $brands);
        
        $reflection = new \ReflectionProperty($widget, 'categories');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $categories);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $subcategory);
        
        $reflection = new \ReflectionProperty($widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $activeStatuses);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'admin-products-filters.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<div class="orders-filters">#', $result);
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
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[active\]\[\]" value="1"> Active</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[active\]\[\]" value="0"> Not active</label>#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[url\]">#', $result);
        $this->assertRegExp('#<input type="submit" value="Применить">#', $result);
        $this->assertRegExp('#<form id="admin-products-filters-clean" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="submit" value="Очистить">#', $result);
    }
}
