<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UsersFiltersWidget;
use app\collections\{SortingFieldsCollection,
    SortingTypesCollection};
use app\forms\{AbstractBaseForm,
    UsersFiltersForm};

/**
 * Тестирует класс UsersFiltersWidget
 */
class UsersFiltersWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new UsersFiltersWidget();
    }
    
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UsersFiltersWidget::class);
        
        $this->assertTrue($reflection->hasProperty('sortingFields'));
        $this->assertTrue($reflection->hasProperty('sortingTypes'));
        $this->assertTrue($reflection->hasProperty('ordersStatuses'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод UsersFiltersWidget::setSortingFields
     */
    public function testSetSortingFields()
    {
        $sortingFields =['name'=>'Name', 'orders'=>'Orders'];
        
        $this->widget->setSortingFields($sortingFields);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод UsersFiltersWidget::setSortingTypes
     */
    public function testSetSortingTypes()
    {
        $sortingTypes = [SORT_ASC=>'asc'];
        
        $this->widget->setSortingTypes($sortingTypes);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод UsersFiltersWidget::setOrdersStatuses
     */
    public function testSetOrdersStatuses()
    {
        $ordersStatuses = [1=>'True'];
        
        $this->widget->setOrdersStatuses($ordersStatuses);
        
        $reflection = new \ReflectionProperty($this->widget, 'ordersStatuses');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод UsersFiltersWidget::setForm
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
     * Тестирует метод UsersFiltersWidget::setHeader
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
     * Тестирует метод UsersFiltersWidget::setTemplate
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
     * Тестирует метод UsersFiltersWidget::run
     * если пуст UsersFiltersWidget::sortingFields
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sortingFields
     */
    public function testRunEmptySortingFields()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод UsersFiltersWidget::run
     * если пуст UsersFiltersWidget::sortingTypes
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sortingTypes
     */
    public function testRunEmptySortingTypes()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод UsersFiltersWidget::run
     * если пуст UsersFiltersWidget::ordersStatuses
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: ordersStatuses
     */
    public function testRunEmptyOrdersStatuses()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод UsersFiltersWidget::run
     * если пуст UsersFiltersWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'ordersStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод UsersFiltersWidget::run
     * если пуст UsersFiltersWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'ordersStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод UsersFiltersWidget::run
     * если пуст UsersFiltersWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'ordersStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод UsersFiltersWidget::run
     */
    public function testRun()
    {
        $sortingFields = ['name'=>'Name', 'orders'=>'Orders'];
        $sortingTypes = [SORT_ASC=>'Sort ascending', SORT_DESC=>'Sort descending'];
        $ordersStatuses = [1=>'True', 0=>'False'];
        
        $form = new class() extends AbstractBaseForm {
            public $sortingField;
            public $sortingType;
            public $ordersStatus;
            public $url = 'https:://shop.com';
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingFields');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $sortingFields);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $sortingTypes);
        
        $reflection = new \ReflectionProperty($this->widget, 'ordersStatuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $ordersStatuses);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'users-filters.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="admin-users-filters-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[sortingField\]">#', $result);
        $this->assertRegExp('#<option value="name">Name</option>#', $result);
        $this->assertRegExp('#<option value="orders">Orders</option>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[sortingType\]">#', $result);
        $this->assertRegExp('#<option value="[0-9]{1}">Sort ascending</option>#', $result);
        $this->assertRegExp('#<option value="[0-9]{1}">Sort descending</option>#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[url\]" value="https:://shop.com">#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[ordersStatus\]\[\]" value="1"> True</label>#', $result);
        $this->assertRegExp('#<label><input type="checkbox" name=".+\[ordersStatus\]\[\]" value="0"> False</label>#', $result);
        $this->assertRegExp('#<input type="submit" value="Применить">#', $result);
        $this->assertRegExp('#<form id="admin-users-filters-clean" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[url\]" value="https:://shop.com">#', $result);
        $this->assertRegExp('#<input type="submit" value="Очистить">#', $result);
    }
}
