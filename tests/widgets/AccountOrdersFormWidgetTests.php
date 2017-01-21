<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountOrdersFormWidget;
use app\models\{CurrencyModel,
    UsersModel};
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\forms\PurchaseForm;

/**
 * Тестирует класс AccountOrdersFormWidget
 */
class AccountOrdersFormWidgetTests extends TestCase
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
     * Тестирует свойства AccountOrdersFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountOrdersFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод AccountOrdersFormWidget::setPurchases
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = new class() {};
        
        $widget = new AccountOrdersFormWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод AccountOrdersFormWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = [new class() {}];
        
        $widget = new AccountOrdersFormWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AccountOrdersFormWidget::setCurrency
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new AccountOrdersFormWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод AccountOrdersFormWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new AccountOrdersFormWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод AccountOrdersFormWidget::setForm
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new AccountOrdersFormWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод AccountOrdersFormWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends PurchaseForm {};
        
        $widget = new AccountOrdersFormWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(PurchaseForm::class, $result);
    }
    
    /**
     * Тестирует метод AccountOrdersFormWidget::run
     * если пуст AccountOrdersFormWidget::purchases
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: purchases
     */
    public function testRunEmptyPurchases()
    {
        $widget = new AccountOrdersFormWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountOrdersFormWidget::run
     * если пуст AccountOrdersFormWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $mock = new class() {};
        
        $widget = new AccountOrdersFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountOrdersFormWidget::run
     * если пуст AccountOrdersFormWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $widget = new AccountOrdersFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountOrdersFormWidget::run
     * если пуст AccountOrdersFormWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $widget = new AccountOrdersFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountOrdersFormWidget::run
     */
    public function testRun()
    {
        $user = UsersModel::findOne(1);
        
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $form = new class() extends PurchaseForm {};
        
        $purchases = [
            new class() {
                public $id = 2;
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 12.89;
                public $canceled = 0;
                public $shipped = 0;
                public $processed = 0;
                public $received = 1;
                public $received_date = 1459112400;
                public function __construct()
                {
                    $this->product = new class() {
                        public $seocode = 'prod_1';
                        public $name = 'Name 1';
                        public $short_description = 'Description 1';
                        public $images = 'test';
                    };
                    $this->color = new class() {
                        public $color = 'gray';
                    };
                    $this->size = new class() {
                        public $size = 45;
                    };
                }
            },
            new class() {
                public $id = 1;
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 56.00;
                public $canceled = 0;
                public $shipped = 0;
                public $processed = 1;
                public $received = 1;
                public $received_date = 1459112400;
                public function __construct()
                {
                    $this->product = new class() {
                        public $seocode = 'prod_2';
                        public $name = 'Name 2';
                        public $short_description = 'Description 2';
                        public $images = 'test';
                    };
                    $this->color = new class() {
                        public $color = 'green';
                    };
                    $this->size = new class() {
                        public $size = 15.5;
                    };
                }
            },
            new class() {
                public $id = 3;
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 56.00;
                public $canceled = 0;
                public $shipped = 1;
                public $processed = 1;
                public $received = 1;
                public $received_date = 1459112400;
                public function __construct()
                {
                    $this->product = new class() {
                        public $seocode = 'prod_3';
                        public $name = 'Name 3';
                        public $short_description = 'Description 3';
                        public $images = 'test';
                    };
                    $this->color = new class() {
                        public $color = 'green';
                    };
                    $this->size = new class() {
                        public $size = 25.5;
                    };
                }
            },
            new class() {
                public $id = 4;
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 526.00;
                public $canceled = 1;
                public $shipped = 0;
                public $processed = 1;
                public $received = 1;
                public $received_date = 1459112400;
                public function __construct()
                {
                    $this->product = new class() {
                        public $seocode = 'prod_4';
                        public $name = 'Name 4';
                        public $short_description = 'Description 4';
                        public $images = 'test';
                    };
                    $this->color = new class() {
                        public $color = 'green';
                    };
                    $this->size = new class() {
                        public $size = 45.5;
                    };
                }
            },
        ];
        
        $widget = new AccountOrdersFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-orders-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Заказы</strong></p>#', $result);
        $this->assertRegExp('#<ol class="account-orders">#', $result);
        $this->assertRegExp('#<li class="account-order-2">#', $result);
        $this->assertRegExp('#<li class="account-order-1">#', $result);
        $this->assertRegExp('#<li class="account-order-3">#', $result);
        $this->assertRegExp('#<li class="account-order-4">#', $result);
        $this->assertRegExp('#<a href=".+">Name 1</a>#', $result);
        $this->assertRegExp('#<a href=".+">Name 2</a>#', $result);
        $this->assertRegExp('#<a href=".+">Name 3</a>#', $result);
        $this->assertRegExp('#<a href=".+">Name 4</a>#', $result);
        $this->assertRegExp('#Description 1#', $result);
        $this->assertRegExp('#Description 2#', $result);
        $this->assertRegExp('#Description 3#', $result);
        $this->assertRegExp('#Description 4#', $result);
        $this->assertRegExp('#<img src=".+" height="200" alt="">#', $result);
        $this->assertRegExp('#Дата заказа:\s.+#', $result);
        $this->assertRegExp('#Цвет:\s.+#', $result);
        $this->assertRegExp('#Размер:\s.+#', $result);
        $this->assertRegExp('#Количество:\s\d+#', $result);
        $this->assertRegExp('#Цена:\s.+\sMONEY#', $result);
        $this->assertRegExp('#Статус:\s<span class="account-order-status">Получен</span>#', $result);
        $this->assertRegExp('#Статус:\s<span class="account-order-status">Выполняется</span>#', $result);
        $this->assertRegExp('#Статус:\s<span class="account-order-status">Доставлен</span>#', $result);
        $this->assertRegExp('#Статус:\s<span class="account-order-status">Отменен</span>#', $result);
        $this->assertRegExp('#<form id="order-cancellation-form-\d{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="\d{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Отменить">#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
