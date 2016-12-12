<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\ToCartWidget;
use yii\base\Model;
use app\forms\PurchaseForm;

/**
 * Тестирует класс ToCartWidget
 */
class ToCartWidgetTests extends TestCase
{
    /**
     * Тестирует свойства ToCartWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ToCartWidget::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод ToCartWidget::setModel
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetModelError()
    {
        $model = new class() {};
        
        $widget = new ToCartWidget();
        $widget->setModel($model);
    }
    
    /**
     * Тестирует метод ToCartWidget::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends Model {};
        
        $widget = new ToCartWidget();
        $widget->setModel($model);
        
        $reflection = new \ReflectionProperty($widget, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод ToCartWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $model = new class() {};
        
        $widget = new ToCartWidget();
        $widget->setForm($model);
    }
    
    /**
     * Тестирует метод ToCartWidget::setForm
     */
    public function testSetForm()
    {
        $model = new class() extends Model {};
        
        $widget = new ToCartWidget();
        $widget->setForm($model);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод ToCartWidget::run
     * если отсутствует ToCartWidget::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: model
     */
    public function testRunEmptyModel()
    {
        $widget = new ToCartWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод ToCartWidget::run
     * если отсутствует ToCartWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: form
     */
    public function testRunEmptyForm()
    {
        $model = new class() extends Model {};
        
        $widget = new ToCartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $model);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ToCartWidget::run
     * если отсутствует ToCartWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $model = new class() extends Model {};
        
        $widget = new ToCartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $model);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $model);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод ToCartWidget::run
     */
    public function testRun()
    {
        $model = new class() extends Model {
            public $id = 23;
            public $price = 56.00;
            public $colors = [['id'=>1, 'color'=>'black'], ['id'=>2, 'color'=>'green']];
            public $sizes = [['id'=>1, 'size'=>56.5], ['id'=>2, 'size'=>42]];
        };
        
        $form = new class() extends PurchaseForm {};
        
        $widget = new ToCartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $model);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'add-to-cart-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<form id="add-to-cart-form"#', $result);
        $this->assertRegExp('#<input type="number"#', $result);
        $this->assertRegExp('#step="1" min="1">#', $result);
        $this->assertRegExp('#<label class="control-label" for=".+">Id Color</label>#', $result);
        $this->assertRegExp('#<option value="0">black</option>#', $result);
        $this->assertRegExp('#<option value="1">green</option>#', $result);
        $this->assertRegExp('#<label class="control-label" for=".+">Id Size</label>#', $result);
        $this->assertRegExp('#<option value="1">56.5</option>#', $result);
        $this->assertRegExp('#<option value="2">42</option>#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+" value="23">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+" value="56">#', $result);
        $this->assertRegExp('#<input type="submit" value="Добавить в корзину">#', $result);
    }
}
