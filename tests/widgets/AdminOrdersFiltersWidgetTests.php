<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminOrdersFiltersWidget;
use app\collections\{SortingFieldsCollection,
    SortingTypesCollection};
use app\forms\AdminOrdersFiltersForm;

/**
 * Тестирует класс AdminOrdersFiltersWidget
 */
class AdminOrdersFiltersWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrdersFiltersWidget::class);
        
        $this->assertTrue($reflection->hasProperty('statuses'));
        $this->assertTrue($reflection->hasProperty('sortingTypes'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::setStatuses
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetStatusesError()
    {
        $statuses = new class() {};
        
        $widget = new AdminOrdersFiltersWidget();
        $widget->setStatuses($statuses);
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::setStatuses
     */
    public function testSetStatuses()
    {
        $statuses = new class() {};
        
        $widget = new AdminOrdersFiltersWidget();
        $widget->setStatuses([$statuses]);
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::setSortingTypes
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSortingTypesError()
    {
        $collection = new class() {};
        
        $widget = new AdminOrdersFiltersWidget();
        $widget->setSortingTypes($collection);
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::setSortingTypes
     */
    public function testSetSortingTypes()
    {
        $collection = [SORT_ASC=>'asc'];
        
        $widget = new AdminOrdersFiltersWidget();
        $widget->setSortingTypes($collection);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new AdminOrdersFiltersWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends AdminOrdersFiltersForm {};
        
        $widget = new AdminOrdersFiltersWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(AdminOrdersFiltersForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new AdminOrdersFiltersWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new AdminOrdersFiltersWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AdminOrdersFiltersWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AdminOrdersFiltersWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::run
     * если пуст AdminOrdersFiltersWidget::statuses
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: statuses
     */
    public function testRunEmptyStatuses()
    {
        $widget = new AdminOrdersFiltersWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::run
     * если пуст AdminOrdersFiltersWidget::sortingTypes
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sortingTypes
     */
    public function testRunEmptySortingTypes()
    {
        $mock = new class() {};
        
        $widget = new AdminOrdersFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::run
     * если пуст AdminOrdersFiltersWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $widget = new AdminOrdersFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::run
     * если пуст AdminOrdersFiltersWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $widget = new AdminOrdersFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::run
     * если пуст AdminOrdersFiltersWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $widget = new AdminOrdersFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrdersFiltersWidget::run
     */
    public function testRun()
    {
        $statuses = ['received'=>'Received', 'processed'=>'Processed'];
        
        $sortingTypes = [SORT_ASC=>'Sort ascending', SORT_DESC=>'Sort descending'];
        
        $form = new class() extends AdminOrdersFiltersForm {};
        
        $widget = new AdminOrdersFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $statuses);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $sortingTypes);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'admin-orders-filters.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="admin-orders-filters-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<label class="control-label" for=".+">Дата заказа</label>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[sortingType\]">#', $result);
        $this->assertRegExp('#<option value="4">Sort ascending</option>#', $result);
        $this->assertRegExp('#<option value="3">Sort descending</option>#', $result);
        //$this->assertRegExp('#<label class="control-label" for=".+">Статус</label>#', $result);
        //$this->assertRegExp('#<select id=".+" class="form-control" name=".+\[status\]">#', $result);
        //$this->assertRegExp('#<option value="received">Received</option>#', $result);
        //$this->assertRegExp('#<option value="processed">Processed</option>#', $result);
        $this->assertRegExp('#<input type="submit" value="Применить">#', $result);
        $this->assertRegExp('#<form id="admin-orders-filters-clean" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="submit" value="Очистить">#', $result);
    }
}
