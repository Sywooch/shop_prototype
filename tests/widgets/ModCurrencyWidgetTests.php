<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\widgets\ModCurrencyWidget;
use app\controllers\ProductsListController;

/**
 * Тестирует класс ModCurrencyWidget
 */
class ModCurrencyWidgetTests extends TestCase
{
    private $widget;
    
    public function setUp()
    {
        $this->widget = new ModCurrencyWidget();
    }
    
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ModCurrencyWidget::class);
        
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('current'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод ModCurrencyWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = [1=>'ONE', 2=>'TWO'];
        
        $this->widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод ModCurrencyWidget::setCurrent
     */
    public function testSetCurrent()
    {
        $current = new class() extends Model {};
        
        $this->widget->setCurrent($current);
        
        $reflection = new \ReflectionProperty($this->widget, 'current');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод ModCurrencyWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $this->widget->setTemplate('template.twig');
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод ModCurrencyWidget::run
     * если пуст ModCurrencyWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModCurrencyWidget::run
     * если пуст ModCurrencyWidget::current
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: current
     */
    public function testRunEmptyCurrent()
    {
        $currency = [1=>'ONE', 2=>'TWO'];
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $currency);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModCurrencyWidget::run
     * если пуст ModCurrencyWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $currency = [1=>'ONE', 2=>'TWO'];
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $currency);
        
        $reflection = new \ReflectionProperty($this->widget, 'current');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод ModCurrencyWidget::run
     */
    public function testRun()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $currency = [
            new class() {
                public $main = 1;
                public $id = 1;
                public $code = 'UAH';
            },
            new class() {
                public $main = 0;
                public $id = 2;
                public $code = 'USD';
            },
            new class() {
                public $main = 0;
                public $id = 3;
                public $code = 'EUR';
            },
        ];
        
        $current = new class() {
            public $main = 0;
            public $id = 2;
            public $code = 'USD';
            public $symbol = '&#36;';
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $currency);
        
        $reflection = new \ReflectionProperty($this->widget, 'current');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $current);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, 'currency-form-mod.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<div id="currency">#', $result);
        $this->assertRegExp('#<ul class="currency-list">#', $result);
        $this->assertRegExp('#<li><span class="currency-button">USD</span>#', $result);
        $this->assertRegExp('#<ul class="currency-not-active disable" data-link=".+" data-action=".+">#', $result);
        $this->assertRegExp('#<li data-id="1"><span class="currency-item">UAH</span></li>#', $result);
        $this->assertRegExp('#<li data-id="3"><span class="currency-item">EUR</span></li>#', $result);
    }
}
