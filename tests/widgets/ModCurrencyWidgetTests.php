<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
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
     * если пуст ModCurrencyWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $currency = [1=>'ONE', 2=>'TWO'];
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $currency);
        
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
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, $currency);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($this->widget, 'currency-form-mod.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<div id="currency">#', $result);
        $this->assertRegExp('#<ul class="currency-list">#', $result);
        $this->assertRegExp('#<li class="currency-button"><span>UAH</span>#', $result);
        $this->assertRegExp('#<ul class="currency-not-active disable" data-link=".+" data-action=".+">#', $result);
        $this->assertRegExp('#<li data-id="2"><span>USD</span></li>#', $result);
        $this->assertRegExp('#<li data-id="3"><span>EUR</span></li>#', $result);
    }
}
