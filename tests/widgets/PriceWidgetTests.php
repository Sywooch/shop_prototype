<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\PriceWidget;
use yii\base\Model;

/**
 * Тестирует класс PriceWidget
 */
class PriceWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PriceWidget::class);
        
        $this->assertTrue($reflection->hasProperty('currencyModel'));
        $this->assertTrue($reflection->hasProperty('price'));
    }
    
    /**
     * Тестирует метод PriceWidget::setCurrencyModel
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyModelError()
    {
        $model = new class() {};
        $widget = new PriceWidget();
        $widget->setCurrencyModel($model);
    }
    
    /**
     * Тестирует метод PriceWidget::setCurrencyModel
     */
    public function testSetCurrencyModel()
    {
        $model = new class() extends Model {};
        $widget = new PriceWidget();
        $widget->setCurrencyModel($model);
        
        $reflection = new \ReflectionProperty($widget, 'currencyModel');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод PriceWidget::run
     * при отсутствии PriceWidget::currencyModel
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: currencyModel
     */
    public function testRunEmptyCurrencyModel()
    {
        $widget = new PriceWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод PriceWidget::run
     * при отсутствии PriceWidget::price
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: price
     */
    public function testRunEmptyPrice()
    {
        $widget = new PriceWidget();
        $model = new class() extends Model {};
        
        $reflection = new \ReflectionProperty($widget, 'currencyModel');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $model);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод PriceWidget::run
     */
    public function testRun()
    {
        $currencyModel = new class() extends Model {
            public $exchange_rate = 4.7864;
            public $code = 'MONEY';
        };
        
        $widget = new PriceWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currencyModel');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $currencyModel);
        
        $reflection = new \ReflectionProperty($widget, 'price');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 8913.56);
        
        $result = $widget->run();
        
        $this->assertSame( \Yii::$app->formatter->asDecimal(8913.56 * 4.7864, 2) . ' MONEY', $result);
    }
}
