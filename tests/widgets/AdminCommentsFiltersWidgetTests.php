<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminCommentsFiltersWidget;
use app\collections\{SortingFieldsCollection,
    SortingTypesCollection};
use app\forms\AbstractBaseForm;
use app\controllers\AdminController;

/**
 * Тестирует класс AdminCommentsFiltersWidget
 */
class AdminCommentsFiltersWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new AdminCommentsFiltersWidget();
        
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
    }
    
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCommentsFiltersWidget::class);
        
        $this->assertTrue($reflection->hasProperty('sortingFields'));
        $this->assertTrue($reflection->hasProperty('sortingTypes'));
        $this->assertTrue($reflection->hasProperty('activeStatuses'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminCommentsFiltersWidget::setSortingFields
     */
    public function testSetSortingFields()
    {
        $sortingFields = [null];
        
        $this->widget->setSortingFields($sortingFields);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminCommentsFiltersWidget::setSortingTypes
     */
    public function testSetSortingTypes()
    {
        $sortingTypes = [null];
        
        $this->widget->setSortingTypes($sortingTypes);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminCommentsFiltersWidget::setActiveStatuses
     */
    public function testSetActiveStatuses()
    {
        $activeStatuses = [null];
        
        $this->widget->setActiveStatuses($activeStatuses);
        
        $reflection = new \ReflectionProperty($this->widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminCommentsFiltersWidget::setForm
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
     * Тестирует метод AdminCommentsFiltersWidget::setHeader
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
     * Тестирует метод AdminCommentsFiltersWidget::setTemplate
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
     * Тестирует метод AdminCommentsFiltersWidget::run
     * если пуст AdminCommentsFiltersWidget::sortingFields
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sortingFields
     */
    public function testRunEmptySortingFields()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentsFiltersWidget::run
     * если пуст AdminCommentsFiltersWidget::sortingTypes
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
     * Тестирует метод AdminCommentsFiltersWidget::run
     * если пуст AdminCommentsFiltersWidget::activeStatuses
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
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentsFiltersWidget::run
     * если пуст AdminCommentsFiltersWidget::form
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
        
        $reflection = new \ReflectionProperty($this->widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentsFiltersWidget::run
     * если пуст AdminCommentsFiltersWidget::header
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
        
        $reflection = new \ReflectionProperty($this->widget, 'activeStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminCommentsFiltersWidget::run
     * если пуст AdminCommentsFiltersWidget::template
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
     * Тестирует метод AdminCommentsFiltersWidget::run
     */
    public function testRun()
    {
        $sortingFields = ['date'=>'Date'];
        $sortingTypes = [SORT_ASC=>'Sort ascending', SORT_DESC=>'Sort descending'];
        $activeStatuses = [1=>'Active', 0=>'Not active'];
        $form = new class() extends AbstractBaseForm {
            public $sortingField;
            public $sortingType;
            public $activeStatus;
            public $url = 'https:://shop.com';
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $sortingFields);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $sortingTypes);
        
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
        $result = $reflection->setValue($this->widget, 'admin-comments-filters.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="admin-comments-filters-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[sortingField\]">#', $result);
        $this->assertRegExp('#<option value="date">Date</option>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[sortingType\]">#', $result);
        $this->assertRegExp('#<option value="4">Sort ascending</option>#', $result);
        $this->assertRegExp('#<option value="3">Sort descending</option>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[activeStatus\]">#', $result);
        $this->assertRegExp('#<option value="1">Active</option>#', $result);
        $this->assertRegExp('#<option value="0">Not active</option>#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[url\]" value=".+">#', $result);
        $this->assertRegExp('#<input type="submit" value="Применить">#', $result);
        $this->assertRegExp('#<form id="admin-comments-filters-clean" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="submit" value="Очистить">#', $result);
    }
}
