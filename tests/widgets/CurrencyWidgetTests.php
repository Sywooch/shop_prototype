<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CurrencyWidget;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Тестирует класс CurrencyWidget
 */
class CurrencyWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencyWidget::class);
        
        $this->assertTrue($reflection->hasProperty('currencyCollection'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод CurrencyWidget::setCurrencyCollection
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyCollectionError()
    {
        $currencyCollection = new class() {};
        $widget = new CurrencyWidget();
        $widget->setCurrencyCollection($currencyCollection);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setCurrencyCollection
     */
    public function testSetCurrencyCollection()
    {
        $currencyCollection = new class() extends BaseCollection {};
        
        $widget = new CurrencyWidget();
        $widget->setCurrencyCollection($currencyCollection);
        
        $reflection = new \ReflectionProperty($widget, 'currencyCollection');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        $widget = new CurrencyWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends Model {};
        $widget = new CurrencyWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод CurrencyWidget::run
     * при условии, что CurrencyWidget::currencyCollection пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: currencyCollection
     */
    public function testRunEmptyCurrencyCollection()
    {
        $widget = new CurrencyWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CurrencyWidget::run
     * при условии, что CurrencyWidget::form пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: form
     */
    public function testRunEmptyForm()
    {
        $currencyCollection = new class() extends BaseCollection {};
        
        $widget = new CurrencyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currencyCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $currencyCollection);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CurrencyWidget::run
     * при условии, что CurrencyWidget::view пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $currencyCollection = new class() extends BaseCollection {};
        
        $form = new class() extends Model {};
        
        $widget = new CurrencyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currencyCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $currencyCollection);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CurrencyWidget::run
     */
    public function testRun()
    {
        $model_1 = new class() {
            public $id = 1;
            public $code = 'ONE';
        };
        $model_2 = new class() {
            public $id = 2;
            public $code = 'TWO';
        };
        
        $currencyCollection = new class() extends BaseCollection {
            public function map(string $key, string $value){
                return ArrayHelper::map($this->items, $key, $value);
            }
            public function sort(string $key, $type=SORT_ASC){
                ArrayHelper::multisort($this->items, $key, $type);
            }
        };
        
        $reflection = new \ReflectionProperty($currencyCollection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($currencyCollection, [$model_1, $model_2]);
        
        $form = new class() extends Model {
            public $id;
        };
        
        $widget = new CurrencyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currencyCollection');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $currencyCollection);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'currency-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('/<p><strong>' . \Yii::t('base', 'Currency:') . '<\/strong><\/p>/', $result);
        $this->assertRegExp('/<form id="set-currency-form"/', $result);
        $this->assertRegExp('/<option value="1">ONE<\/option>/', $result);
        $this->assertRegExp('/<option value="2">TWO<\/option>/', $result);
        $this->assertRegExp('/<input type="submit" value="' . \Yii::t('base', 'Change') . '">/', $result);
    }
}
