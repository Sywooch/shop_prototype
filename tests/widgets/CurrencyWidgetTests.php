<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CurrencyWidget;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\services\ServiceInterface;
use app\models\CurrencyModel;
use app\controllers\ProductsListController;

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
        $this->assertTrue($reflection->hasProperty('service'));
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
     * Тестирует метод CurrencyWidget::setService
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetServiceError()
    {
        $service = new class() {};
        
        $widget = new CurrencyWidget();
        $widget->setService($service);
    }
    
    /**
     * Тестирует метод CurrencyWidget::setService
     */
    public function testSetService()
    {
        $service = new class() implements ServiceInterface {
            public function handle($data) {}
        };
        
        $widget = new CurrencyWidget();
        $widget->setService($service);
        
        $reflection = new \ReflectionProperty($widget, 'service');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(ServiceInterface::class, $result);
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
     * если пуст CurrencyWidget::service
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: service
     */
    public function testRunEmptyService()
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
        
        $service = new class() implements ServiceInterface {
            public function handle($data) {}
        };
        
        $widget = new CurrencyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'service');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $service);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CurrencyWidget::run
     */
    public function testRun()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $currency = [1=>'ONE', 2=>'TWO'];
        
        $service = new class() implements ServiceInterface {
            public function handle($data=null) {
                return new CurrencyModel(['id'=>1]);
            }
        };
        
        $widget = new CurrencyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'service');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $service);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'currency-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Валюта</strong></p>#', $result);
        $this->assertRegExp('#<form id="set-currency-form"#', $result);
        $this->assertRegExp('#<option value="1" selected>ONE</option>#', $result);
        $this->assertRegExp('#<option value="2">TWO</option>#', $result);
        $this->assertRegExp('#<input type="submit" value="Изменить">#', $result);
    }
}
