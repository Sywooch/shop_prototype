<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountGeneralWidget;
use app\models\{CurrencyModel,
    UsersModel};
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;

/**
 * Тестирует класс AccountGeneralWidget
 */
class AccountGeneralWidgetTests extends TestCase
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
     * Тестирует свойства AccountGeneralWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountGeneralWidget::class);
        
        $this->assertTrue($reflection->hasProperty('user'));
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод AccountGeneralWidget::setUser
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetUserError()
    {
        $user = new class() {};
        
        $widget = new AccountGeneralWidget();
        $widget->setUser($user);
    }
    
    /**
     * Тестирует метод AccountGeneralWidget::setUser
     */
    public function testSetUser()
    {
        $user = new class() extends UsersModel {};
        
        $widget = new AccountGeneralWidget();
        $widget->setUser($user);
        
        $reflection = new \ReflectionProperty($widget, 'user');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(UsersModel::class, $result);
    }
    
    /**
     * Тестирует метод AccountGeneralWidget::setPurchases
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = new class() {};
        
        $widget = new AccountGeneralWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод AccountGeneralWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = [new class() {}];
        
        $widget = new AccountGeneralWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AccountGeneralWidget::setCurrency
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new AccountGeneralWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод AccountGeneralWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new AccountGeneralWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод AccountGeneralWidget::run
     * если пуст AccountGeneralWidget::user
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: user
     */
    public function testRunEmptyUser()
    {
        $widget = new AccountGeneralWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountGeneralWidget::run
     * если пуст AccountGeneralWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $mock = new class() {};
        
        $widget = new AccountGeneralWidget();
        
        $reflection = new \ReflectionProperty($widget, 'user');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountGeneralWidget::run
     * если пуст AccountGeneralWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $widget = new AccountGeneralWidget();
        
        $reflection = new \ReflectionProperty($widget, 'user');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountGeneralWidget::run
     * если нет неотправленных покупок
     */
    public function testRunNotProcessedPurchases()
    {
        $user = UsersModel::findOne(1);
        
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $widget = new AccountGeneralWidget();
        
        $reflection = new \ReflectionProperty($widget, 'user');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $user);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-general.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Текущие контактные данные</strong></p>#', $result);
        $this->assertRegExp('#<div class="account-user-info">#', $result);
        $this->assertRegExp('#Email:</strong> light@mail.some<br>#', $result);
        $this->assertRegExp('#<strong>Имя:</strong> John<br>#', $result);
        $this->assertRegExp('#<strong>Фамилия:</strong> Doe<br>#', $result);
        $this->assertRegExp('#<strong>Телефон:</strong> \+290865687812<br>#', $result);
        $this->assertRegExp('#<strong>Адрес:</strong> Main Street Kangaroo Point<br>#', $result);
        $this->assertRegExp('#<strong>Город:</strong> New York<br>#', $result);
        $this->assertRegExp('#<strong>Страна:</strong> USA<br>#', $result);
        $this->assertRegExp('#<strong>Почтовый код:</strong> 09100<br>#', $result);
    }
    
    /**
     * Тестирует метод AccountGeneralWidget::run
     * если есть неотправленные покупки
     */
    public function testRunExistProcessedPurchases()
    {
        $user = UsersModel::findOne(1);
        
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $purchases = [
            new class() {
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 12.89;
                public $canceled = 0;
                public $shipped = 0;
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
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 56.00;
                public $canceled = 0;
                public $shipped = 0;
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
        ];
        
        $widget = new AccountGeneralWidget();
        
        $reflection = new \ReflectionProperty($widget, 'user');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $user);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-general.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Текущие контактные данные</strong></p>#', $result);
        $this->assertRegExp('#<div class="account-user-info">#', $result);
        $this->assertRegExp('#Email:</strong> light@mail.some<br>#', $result);
        $this->assertRegExp('#<strong>Имя:</strong> John<br>#', $result);
        $this->assertRegExp('#<strong>Фамилия:</strong> Doe<br>#', $result);
        $this->assertRegExp('#<strong>Телефон:</strong> \+290865687812<br>#', $result);
        $this->assertRegExp('#<strong>Адрес:</strong> Main Street Kangaroo Point<br>#', $result);
        $this->assertRegExp('#<strong>Город:</strong> New York<br>#', $result);
        $this->assertRegExp('#<strong>Страна:</strong> USA<br>#', $result);
        $this->assertRegExp('#<strong>Почтовый код:</strong> 09100<br>#', $result);
        
        $this->assertRegExp('#<p><strong>Текущие заказы</strong></p>#', $result);
        $this->assertRegExp('#<div class="account-last-orders">#', $result);
        $this->assertRegExp('#<a href="../vendor/phpunit/phpunit/prod_1">Name 1</a>#', $result);
        $this->assertRegExp('#<br>Description 1#', $result);
        $this->assertRegExp('#<br><img src=".+" height="200" alt="">#', $result);
        $this->assertRegExp('#<br>Цвет: gray#', $result);
        $this->assertRegExp('#<br>Размер: 45#', $result);
        $this->assertRegExp('#<br>Количество: 1#', $result);
        $this->assertRegExp('#<br>Цена: 26,94 MONEY#', $result);
        $this->assertRegExp('#<br>Статус: Выполняется#', $result);
        $this->assertRegExp('#<a href="../vendor/phpunit/phpunit/prod_2">Name 2</a>#', $result);
        $this->assertRegExp('#<br>Description 2#', $result);
        $this->assertRegExp('#<br><img src=".+" height="200" alt="">#', $result);
        $this->assertRegExp('#<br>Цвет: green#', $result);
        $this->assertRegExp('#<br>Размер: 15.5#', $result);
        $this->assertRegExp('#<br>Количество: 1#', $result);
        $this->assertRegExp('#<br>Цена: 117,04 MONEY#', $result);
        $this->assertRegExp('#<br>Статус: Выполняется#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
