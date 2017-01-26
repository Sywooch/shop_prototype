<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CartCheckoutWidget;
use app\forms\CustomerInfoForm;
use app\models\{CurrencyModel,
    UsersModel};
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;

/**
 * Тестирует класс CartCheckoutWidget
 */
class CartCheckoutWidgetTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
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
        $this->assertTrue($reflection->hasProperty('template'));
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
     * Тестирует метод CartCheckoutWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new CartCheckoutWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new CartCheckoutWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
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
     * если пуст CartCheckoutWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
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
     * если isGuest is true
     */
    public function testRunGuest()
    {
        \Yii::$app->user->logout();
        
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
        
        $currency = new class() extends CurrencyModel {
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
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'cart-checkout-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Контактная информация</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>Адрес доставки</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>Тип доставки</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>Форма оплаты</strong></p>#', $result);
        $this->assertRegExp('#<form id="cart-сheckout-ajax-form"#', $result);
        $this->assertRegExp('#<label .+>Name</label>#', $result);
        $this->assertRegExp('#<label .+>Surname</label>#', $result);
        $this->assertRegExp('#<label .+>Email</label>#', $result);
        $this->assertRegExp('#<label .+>Phone</label>#', $result);
        $this->assertRegExp('#<label .+>Address</label>#', $result);
        $this->assertRegExp('#<label .+>City</label>#', $result);
        $this->assertRegExp('#<label .+>Country</label>#', $result);
        $this->assertRegExp('#<label .+>Postcode</label>#', $result);
        $this->assertRegExp('#<label .+>Id Delivery</label>#', $result);
        $this->assertRegExp('#<label .+>Id Payment</label>#', $result);
        $this->assertRegExp('#<input type="submit" value="Отправить заказ">#', $result);
        $this->assertRegExp('#<label><input type="checkbox".+> Create</label>#', $result);
        $this->assertRegExp('#<div class="cart-create-user disable">#', $result);
        $this->assertRegExp('#<label.+>Password</label>#', $result);
        $this->assertRegExp('#<label.+>Password2</label>#', $result);
        
        $this->assertNotRegExp('#<input .+ readonly>#', $result);
        
    }
    
    /**
     * Тестирует метод CartCheckoutWidget::run
     * если isGuest is false
     */
    public function testRunNotGuest()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
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
        
        $currency = new class() extends CurrencyModel {
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
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'cart-checkout-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Контактная информация</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>Адрес доставки</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>Тип доставки</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>Форма оплаты</strong></p>#', $result);
        $this->assertRegExp('#<form id="cart-сheckout-ajax-form"#', $result);
        $this->assertRegExp('#<label .+>Name</label>#', $result);
        $this->assertRegExp('#<label .+>Surname</label>#', $result);
        $this->assertRegExp('#<label .+>Email</label>#', $result);
        $this->assertRegExp('#<label .+>Phone</label>#', $result);
        $this->assertRegExp('#<label .+>Address</label>#', $result);
        $this->assertRegExp('#<label .+>City</label>#', $result);
        $this->assertRegExp('#<label .+>Country</label>#', $result);
        $this->assertRegExp('#<label .+>Postcode</label>#', $result);
        $this->assertRegExp('#<label .+>Id Delivery</label>#', $result);
        $this->assertRegExp('#<label .+>Id Payment</label>#', $result);
        $this->assertRegExp('#<input type="submit" value="Отправить заказ">#', $result);
        $this->assertRegExp('#<input .+ readonly>#', $result);
        
        $this->assertNotRegExp('#<label><input type="checkbox".+> Create</label>#', $result);
        $this->assertNotRegExp('#<div class="cart-create-user disable">#', $result);
        $this->assertNotRegExp('#<label.+>Password</label>#', $result);
        $this->assertNotRegExp('#<label.+>Password2</label>#', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
