<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\PurchaseFormWidget;
use yii\base\Model;
use app\forms\PurchaseForm;
use app\collections\{BaseCollection,
    CollectionInterface};
use app\models\ProductsModel;

/**
 * Тестирует класс PurchaseFormWidget
 */
class PurchaseFormWidgetTests extends TestCase
{
    /**
     * Тестирует свойства PurchaseFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchaseFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('product'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод PurchaseFormWidget::setProduct
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProductError()
    {
        $product = new class() {};
        
        $widget = new PurchaseFormWidget();
        $widget->setProduct($product);
    }
    
    /**
     * Тестирует метод PurchaseFormWidget::setProduct
     */
    public function testSetProduct()
    {
        $product = new class() extends ProductsModel {};
        
        $widget = new PurchaseFormWidget();
        $widget->setProduct($product);
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchaseFormWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new PurchaseFormWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод PurchaseFormWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends PurchaseForm {};
        
        $widget = new PurchaseFormWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(PurchaseForm::class, $result);
    }
    
    /**
     * Тестирует метод PurchaseFormWidget::run
     * если отсутствует PurchaseFormWidget::product
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: product
     */
    public function testRunEmptyProduct()
    {
        $widget = new PurchaseFormWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод PurchaseFormWidget::run
     * если отсутствует PurchaseFormWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $product = new class() extends ProductsModel {};
        
        $widget = new PurchaseFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $product);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод PurchaseFormWidget::run
     * если отсутствует PurchaseFormWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $product = new class() extends ProductsModel {};
        $form = new class() extends PurchaseForm {};
        
        $widget = new PurchaseFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $product);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод PurchaseFormWidget::run
     */
    public function testRun()
    {
        $colors = [
            ['id'=>1, 'color'=>'black'],
            ['id'=>2, 'color'=>'red']
        ];
        
        $sizes = [
            ['id'=>1, 'size'=>45],
            ['id'=>2, 'size'=>52.5]
        ];
        
        $product = new class() {
            public $id = 23;
            public $price = 56.00;
            public $colors;
            public $sizes;
        };
        
        $form = new class() extends PurchaseForm {};
        
        $reflection = new \ReflectionProperty($product, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($product, $colors);
        
        $reflection = new \ReflectionProperty($product, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($product, $sizes);
        
        $form = new class() extends PurchaseForm {};
        
        $widget = new PurchaseFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $product);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'purchase-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<form id="purchase-form"#', $result);
        $this->assertRegExp('#<input type="number"#', $result);
        $this->assertRegExp('#step="1" min="1">#', $result);
        $this->assertRegExp('#<label class="control-label" for=".+">Id Color</label>#', $result);
        $this->assertRegExp('#<option value="1">black</option>#', $result);
        $this->assertRegExp('#<option value="2">red</option>#', $result);
        $this->assertRegExp('#<label class="control-label" for=".+">Id Size</label>#', $result);
        $this->assertRegExp('#<option value="1">45</option>#', $result);
        $this->assertRegExp('#<option value="2">52.5</option>#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+" value="23">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+" value="56">#', $result);
        $this->assertRegExp('#<input type="submit" value="Добавить в корзину">#', $result);
    }
}
