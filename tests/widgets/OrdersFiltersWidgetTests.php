<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\OrdersFiltersWidget;
use app\collections\{SortingFieldsCollection,
    SortingTypesCollection};
use app\forms\OrdersFiltersForm;

/**
 * Тестирует класс OrdersFiltersWidget
 */
class OrdersFiltersWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(OrdersFiltersWidget::class);
        
        $this->assertTrue($reflection->hasProperty('statuses'));
        $this->assertTrue($reflection->hasProperty('sortingTypes'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::setStatuses
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetStatusesError()
    {
        $statuses = new class() {};
        
        $widget = new OrdersFiltersWidget();
        $widget->setStatuses($statuses);
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::setStatuses
     */
    public function testSetStatuses()
    {
        $statuses = new class() {};
        
        $widget = new OrdersFiltersWidget();
        $widget->setStatuses([$statuses]);
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::setSortingTypes
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSortingTypesError()
    {
        $collection = new class() {};
        
        $widget = new OrdersFiltersWidget();
        $widget->setSortingTypes($collection);
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::setSortingTypes
     */
    public function testSetSortingTypes()
    {
        $collection = [SORT_ASC=>'asc'];
        
        $widget = new OrdersFiltersWidget();
        $widget->setSortingTypes($collection);
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new OrdersFiltersWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends OrdersFiltersForm {};
        
        $widget = new OrdersFiltersWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(OrdersFiltersForm::class, $result);
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new OrdersFiltersWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new OrdersFiltersWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new OrdersFiltersWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new OrdersFiltersWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::run
     * если пуст OrdersFiltersWidget::sortingTypes
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sortingTypes
     */
    public function testRunEmptySortingTypes()
    {
        $mock = new class() {};
        
        $widget = new OrdersFiltersWidget();
        
        $widget->run();
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::run
     * если пуст OrdersFiltersWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $widget = new OrdersFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::run
     * если пуст OrdersFiltersWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $widget = new OrdersFiltersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::run
     * если пуст OrdersFiltersWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $widget = new OrdersFiltersWidget();
        
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
     * Тестирует метод OrdersFiltersWidget::run
     */
    public function testRun()
    {
        $statuses = ['received'=>'Received', 'processed'=>'Processed'];
        
        $sortingTypes = [SORT_ASC=>'Sort ascending', SORT_DESC=>'Sort descending'];
        
        $form = new class() extends OrdersFiltersForm {};
        
        $widget = new OrdersFiltersWidget();
        
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
        $result = $reflection->setValue($widget, 'orders-filters.twig');
        
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
