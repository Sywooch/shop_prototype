<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CurrencyWidget;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\forms\ChangeCurrencyForm;

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
        
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод CurrencyWidget::setCurrency
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $model = new class() {};
        
        $widget = new CurrencyWidget();
        $widget->setCurrency($model);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = [1=>'ONE', 2=>'TWO'];
        
        $widget = new CurrencyWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
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
        $form = new class() extends ChangeCurrencyForm {};
        
        $widget = new CurrencyWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(ChangeCurrencyForm::class, $result);
    }
    
    /**
     * Тестирует метод CurrencyWidget::run
     * если пуст CurrencyWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: currency
     */
    public function testRunEmptyCurrency()
    {
        $widget = new CurrencyWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CurrencyWidget::run
     * если пуст CurrencyWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: form
     */
    public function testRunEmptyForm()
    {
        $currency = [1=>'ONE', 2=>'TWO'];
        
        $widget = new CurrencyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $currency);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CurrencyWidget::run
     * если пуст CurrencyWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $currency = [1=>'ONE', 2=>'TWO'];
        
        $form = new class() extends ChangeCurrencyForm {};
        
        $widget = new CurrencyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $currency);
        
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
        $currency = [1=>'ONE', 2=>'TWO'];
        
        $form = new class() extends ChangeCurrencyForm {};
        
        $widget = new CurrencyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'currency-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Валюта</strong></p>#', $result);
        $this->assertRegExp('#<form id="set-currency-form"#', $result);
        $this->assertRegExp('#<option value="1">ONE</option>#', $result);
        $this->assertRegExp('#<option value="2">TWO</option>#', $result);
        $this->assertRegExp('#<input type="submit" value="Изменить">#', $result);
    }
}
