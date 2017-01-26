<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\EmailReceivedOrderWidget;
use app\collections\{AbstractBaseCollection,
    PurchasesCollection};
use app\forms\CustomerInfoForm;
use app\models\CurrencyModel;
use app\tests\DbManager;
use app\tests\sources\fixtures\{DeliveriesFixture,
    PaymentsFixture};

/**
 * Тестирует класс EmailReceivedOrderWidget
 */
class EmailReceivedOrderWidgetTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'deliveries'=>DeliveriesFixture::class,
                'payments'=>PaymentsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства EmailReceivedOrderWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmailReceivedOrderWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод EmailReceivedOrderWidget::setPurchases
     * если передан параметр неврного типа
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = new class() {};
        
        $widget = new EmailReceivedOrderWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод EmailReceivedOrderWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = new class() extends PurchasesCollection {};
        
        $widget = new EmailReceivedOrderWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    /**
     * Тестирует метод EmailReceivedOrderWidget::setForm
     * если передан параметр неврного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new EmailReceivedOrderWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод EmailReceivedOrderWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends CustomerInfoForm {};
        
        $widget = new EmailReceivedOrderWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CustomerInfoForm::class, $result);
    }
    
    /**
     * Тестирует метод EmailReceivedOrderWidget::setCurrency
     * если передан параметр неврного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new EmailReceivedOrderWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод EmailReceivedOrderWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new EmailReceivedOrderWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод EmailReceivedOrderWidget::run
     * если пуст EmailReceivedOrderWidget::purchases
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: purchases
     */
    public function testRunPurchasesEmpty()
    {
        $widget = new EmailReceivedOrderWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод EmailReceivedOrderWidget::run
     * если пуст EmailReceivedOrderWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunFormEmpty()
    {
        $mock = new class() {};
        
        $widget = new EmailReceivedOrderWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод EmailReceivedOrderWidget::run
     * если пуст EmailReceivedOrderWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunCurrencyEmpty()
    {
        $mock = new class() {};
        
        $widget = new EmailReceivedOrderWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод EmailReceivedOrderWidget::run
     * если пуст EmailReceivedOrderWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunViewEmpty()
    {
        $mock = new class() {};
        
        $widget = new EmailReceivedOrderWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод EmailReceivedOrderWidget::run
     */
    public function testRun()
    {
        $items = [
            new class() {
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 23.78;
                public function __construct()
                {
                    $this->product = new class() {
                        public $seocode = 'seocode_1';
                        public $name = 'Name 1';
                        public $short_description = 'short_description 1';
                    };
                    $this->color = new class() {
                        public $color = 'black';
                    };
                    $this->size = new class() {
                        public $size = 45;
                    };
                }
            },
            new class() {
                public $product;
                public $color;
                public $size;
                public $quantity = 2;
                public $price = 59.00;
                public function __construct()
                {
                    $this->product = new class() {
                        public $seocode = 'seocode_2';
                        public $name = 'Name 2';
                        public $short_description = 'short_description 2';
                    };
                    $this->color = new class() {
                        public $color = 'gray';
                    };
                    $this->size = new class() {
                        public $size = 42.5;
                    };
                }
            }
        ];
        
        $purchases = new class() extends PurchasesCollection {
            protected $items = [];
        };
        $reflection = new \ReflectionProperty($purchases, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($purchases, $items);
        
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 1.00;
            public $code = 'MONEY';
        };
        
        $form = new class() {
            public $name = 'John';
            public $surname = 'Doe';
            public $email = 'some@some.com';
            public $phone = '789654';
            public $address = 'some str. 1';
            public $city = 'New York';
            public $country = 'Ukraine';
            public $postcode = '569877J';
            public $id_delivery = 1;
            public $id_payment = 1;
        };
        
        $widget = new EmailReceivedOrderWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'email-received-order-mail.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<h1>Привет! Это информация о вашем заказе!</h1>#', $result);
        $this->assertRegExp('#<a href=".+/seocode_1">Name 1</a>#', $result);
        $this->assertRegExp('#short_description 1#', $result);
        $this->assertRegExp('#Цвет: black#', $result);
        $this->assertRegExp('#Размер: 45#', $result);
        $this->assertRegExp('#Цена: 23,78 MONEY#', $result);
        $this->assertRegExp('#<a href=".+/seocode_2">Name 2</a>#', $result);
        $this->assertRegExp('#short_description 2#', $result);
        $this->assertRegExp('#Цвет: gray#', $result);
        $this->assertRegExp('#Размер: 42.5#', $result);
        $this->assertRegExp('#Количество: 1#', $result);
        $this->assertRegExp('#Цена: 59,00 MONEY#', $result);
        $this->assertRegExp('#<p><strong>Всего товаров: 3, Общая стоимость: 141,78 MONEY</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>Адрес доставки</strong></p>#', $result);
        $this->assertRegExp('#Имя: John#', $result);
        $this->assertRegExp('#<br>Фамилия: Doe#', $result);
        $this->assertRegExp('#Email: some@some.com#', $result);
        $this->assertRegExp('#Телефон: 789654#', $result);
        $this->assertRegExp('#Адрес: some str. 1#', $result);
        $this->assertRegExp('#Город: New York#', $result);
        $this->assertRegExp('#Страна: Ukraine#', $result);
        $this->assertRegExp('#Почтовый код: 569877J#', $result);
        $this->assertRegExp('#Доставка: Доставка по указанному адресу в течение 3-х дней.#', $result);
        $this->assertRegExp('#Оплата: Оплата наличными при получении товара#', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
