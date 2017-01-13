<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CartCheckoutWidget;
use app\forms\CustomerInfoForm;
use app\models\CurrencyModel;

/**
 * Тестирует класс CartCheckoutWidget
 */
class CartCheckoutWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CartCheckoutWidget::class);
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('deliveries'));
        $this->assertTrue($reflection->hasProperty('payments'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new CartCheckoutWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends CustomerInfoForm {};
        
        $widget = new CartCheckoutWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CustomerInfoForm::class, $result);
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::setDeliveries
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetDeliveriesError()
    {
        $deliveries = new class() {};
        
        $widget = new CartCheckoutWidget();
        $widget->setDeliveries($deliveries);
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::setDeliveries
     */
    public function testSetDeliveries()
    {
        $deliveries = [new class() {}];
        
        $widget = new CartCheckoutWidget();
        $widget->setDeliveries($deliveries);
        
        $reflection = new \ReflectionProperty($widget, 'deliveries');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::setPayments
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPaymentsError()
    {
        $payments = new class() {};
        
        $widget = new CartCheckoutWidget();
        $widget->setPayments($payments);
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::setPayments
     */
    public function testSetPayments()
    {
        $payments = [new class() {}];
        
        $widget = new CartCheckoutWidget();
        $widget->setPayments($payments);
        
        $reflection = new \ReflectionProperty($widget, 'payments');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::setCurrency
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new CartCheckoutWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new CartCheckoutWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::run
     * если пуст CartCheckoutWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $widget = new CartCheckoutWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::run
     * если пуст CartCheckoutWidget::deliveries
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: deliveries
     */
    public function testRunEmptyDeliveries()
    {
        $mock = new class() {};
        
        $widget = new CartCheckoutWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::run
     * если пуст CartCheckoutWidget::payments
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: payments
     */
    public function testRunEmptyPayments()
    {
        $mock = new class() {};
        
        $widget = new CartCheckoutWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'deliveries');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::run
     * если пуст CartCheckoutWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $mock = new class() {};
        
        $widget = new CartCheckoutWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'deliveries');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'payments');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::run
     * если пуст CartCheckoutWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $widget = new CartCheckoutWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'deliveries');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'payments');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::run
     */
    public function testRun()
    {
        $form = new class() extends CustomerInfoForm {};
        
        $deliveries = [
            new class() {
                public $id = 1;
                public $description = 'Some description';
                public $price = 58.00;
            },
            new class() {
                public $id = 2;
                public $description = 'Another some description';
                public $price = 654.04;
            },
        ];
        
        $payments = [
            new class() {
                public $id = 1;
                public $description = 'Some description';
            },
            new class() {
                public $id = 2;
                public $description = 'Another some description';
            },
        ];
        
        $currency = new class() {
            public $exchange_rate = 12.865412;
            public $code = 'MONEY';
        };
        
        $widget = new CartCheckoutWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'deliveries');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $deliveries);
        
        $reflection = new \ReflectionProperty($widget, 'payments');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $payments);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'cart-checkout-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Оформить заказ</strong></p>#', $result);
        $this->assertRegExp('#<form id="cart-checkout-form"#', $result);
        $this->assertRegExp('#<label .+>Name</label>#', $result);
        $this->assertRegExp('#<label .+>Surname</label>#', $result);
        $this->assertRegExp('#<label .+>Email</label>#', $result);
        $this->assertRegExp('#<label .+>Phone</label>#', $result);
        $this->assertRegExp('#<label .+>Address</label>#', $result);
        $this->assertRegExp('#<label .+>City</label>#', $result);
        $this->assertRegExp('#<label .+>Country</label>#', $result);
        $this->assertRegExp('#<label .+>Postcode</label>#', $result);
        $this->assertRegExp('#<label .+>Delivery</label>#', $result);
        $this->assertRegExp('#<label .+>Payment</label>#', $result);
        $this->assertRegExp('#<input type="submit" value="Оформить заказ">#', $result);
        $this->assertRegExp('#<form id="back-to-cart"#', $result);
        $this->assertRegExp('#<input type="submit" value="В корзину">#', $result);
    }
}
