<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\OrdersFiltersWidget;
use app\collections\{SortingFieldsCollection,
    SortingTypesCollection};
use app\forms\OrdersFiltersForm;
use app\controllers\AdminController;

/**
 * Тестирует класс OrdersFiltersWidget
 */
class OrdersFiltersWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
        
        $this->widget = new OrdersFiltersWidget();
    }
    
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
     */
    public function testSetStatuses()
    {
        $statuses = new class() {};
        
        $this->widget->setStatuses([$statuses]);
        
        $reflection = new \ReflectionProperty($this->widget, 'statuses');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::setSortingTypes
     */
    public function testSetSortingTypes()
    {
        $collection = [SORT_ASC=>'asc'];
        
        $this->widget->setSortingTypes($collection);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends OrdersFiltersForm {};
        
        $this->widget->setForm($form);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(OrdersFiltersForm::class, $result);
    }
    
    /**
     * Тестирует метод OrdersFiltersWidget::setHeader
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
     * Тестирует метод OrdersFiltersWidget::setTemplate
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
     * Тестирует метод OrdersFiltersWidget::run
     * если пуст OrdersFiltersWidget::statuses
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: statuses
     */
    public function testRunEmptyStatuses()
    {
        $this->widget->run();
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
        
        $reflection = new \ReflectionProperty($this->widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $this->widget->run();
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
        
        $reflection = new \ReflectionProperty($this->widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $this->widget->run();
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
        
        $reflection = new \ReflectionProperty($this->widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $this->widget->run();
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
        
        $reflection = new \ReflectionProperty($this->widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
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
     * Тестирует метод OrdersFiltersWidget::run
     */
    public function testRun()
    {
        $statuses = ['received'=>'Received', 'processed'=>'Processed'];
        
        $sortingTypes = [SORT_ASC=>'Sort ascending', SORT_DESC=>'Sort descending'];
        
        $form = new class() extends OrdersFiltersForm {};
        
        $reflection = new \ReflectionProperty($this->widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $statuses);
        
        $reflection = new \ReflectionProperty($this->widget, 'sortingTypes');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $sortingTypes);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, 'orders-filters.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<form id="admin-orders-filters-form" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<label class="control-label" for=".+">Сортировать по дате</label>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[sortingType\]">#', $result);
        $this->assertRegExp('#<option value="4">Sort ascending</option>#', $result);
        $this->assertRegExp('#<option value="3">Sort descending</option>#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[url\]" value=".+">#', $result);
        $this->assertRegExp('#<p><a href=".+" data-timestamp="[0-9]{10}" class="calendar-href-from">[0-9]{1,2} .+ [0-9]{4} г\.</a> &ndash; <a href=".+" data-timestamp="[0-9]{10}" class="calendar-href-to">[0-9]{1,2} .+ [0-9]{4} г\.</a></p>#', $result);
        $this->assertRegExp('#<p class="calendar-place"></p>#', $result);
        $this->assertRegExp('#<label class="control-label" for=".+">Статус</label>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[status\]">#', $result);
        $this->assertRegExp('#<option value="received">Received</option>#', $result);
        $this->assertRegExp('#<option value="processed">Processed</option>#', $result);
        $this->assertRegExp('#<input type="submit" value="Применить">#', $result);
        $this->assertRegExp('#<form id="admin-orders-filters-clean" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="submit" value="Очистить">#', $result);
    }
}
