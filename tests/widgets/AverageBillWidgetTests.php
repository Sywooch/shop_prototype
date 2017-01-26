<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AverageBillWidget;
use app\collections\{PurchasesCollection,
    PurchasesCollectionInterface};
use app\models\CurrencyModel;

/**
 * Тестирует класс AverageBillWidget
 */
class AverageBillWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AverageBillWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AverageBillWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод AverageBillWidget::setPurchases
     * передаю неверный тип параметра
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = new class() {};
        
        $widget = new AverageBillWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод AverageBillWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = new class() extends PurchasesCollection{};
        
        $widget = new AverageBillWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод AverageBillWidget::setCurrency
     * передаю неверный тип параметра
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currenct = new class() {};
        
        $widget = new AverageBillWidget();
        $widget->setCurrency($currenct);
    }
    
    /**
     * Тестирует метод AverageBillWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel{};
        
        $widget = new AverageBillWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод AverageBillWidget::run
     * если пуст AverageBillWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $widget = new AverageBillWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AverageBillWidget::run
     * если пуст AverageBillWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $widget = new AverageBillWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AverageBillWidget::run
     * если покупок нет
     */
    public function testRunEmpty()
    {
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.23;
            public $code = 'MONEY';
        };
        
        $purchases = new class() {
            public function isEmpty()
            {
                return true;
            }
        };
        
        $widget = new AverageBillWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'average-bill.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Средний чек</strong></p>#', $result);
        $this->assertRegExp('#<p>Средний чек сегодня: 0,00 MONEY</p>#', $result);
    }
    
    /**
     * Тестирует метод AverageBillWidget::run
     */
    public function testRun()
    {
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.23;
            public $code = 'MONEY';
        };
        
        $purchases = new class() {
            public function isEmpty()
            {
                return false;
            }
            public function totalPrice()
            {
                return 100.00;
            }
            public function count()
            {
                return 3;
            }
        };
        
        $widget = new AverageBillWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'average-bill.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Средний чек</strong></p>#', $result);
        $this->assertRegExp('#<p>Средний чек сегодня: 74,33 MONEY</p>#', $result);
    }
}
