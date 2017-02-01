<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountCurrentOrdersWidget;
use app\models\{CurrencyModel,
    UsersModel};
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;

/**
 * Тестирует класс AccountCurrentOrdersWidget
 */
class AccountCurrentOrdersWidgetTests extends TestCase
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
     * Тестирует свойства AccountCurrentOrdersWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountCurrentOrdersWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AccountCurrentOrdersWidget::setPurchases
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = new class() {};
        
        $widget = new AccountCurrentOrdersWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод AccountCurrentOrdersWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = [new class() {}];
        
        $widget = new AccountCurrentOrdersWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AccountCurrentOrdersWidget::setCurrency
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new AccountCurrentOrdersWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод AccountCurrentOrdersWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new AccountCurrentOrdersWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод AccountCurrentOrdersWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new AccountCurrentOrdersWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод AccountCurrentOrdersWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new AccountCurrentOrdersWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AccountCurrentOrdersWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AccountCurrentOrdersWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AccountCurrentOrdersWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AccountCurrentOrdersWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AccountCurrentOrdersWidget::run
     * если пуст AccountCurrentOrdersWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $widget = new AccountCurrentOrdersWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountCurrentOrdersWidget::run
     * если пуст AccountCurrentOrdersWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $widget = new AccountCurrentOrdersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountCurrentOrdersWidget::run
     * если пуст AccountCurrentOrdersWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $widget = new AccountCurrentOrdersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountCurrentOrdersWidget::run
     * если нет неотправленных покупок
     */
    public function testRunNotProcessedPurchases()
    {
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $widget = new AccountCurrentOrdersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-current-orders.twig');
        
        $result = $widget->run();
        
        $this->assertEmpty($result);
    }
    
    /**
     * Тестирует метод AccountCurrentOrdersWidget::run
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
                public $id = 1;
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 12.89;
                public $canceled = 0;
                public $shipped = 0;
                public $processed = 0;
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
                public $id = 2;
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 56.00;
                public $canceled = 0;
                public $shipped = 0;
                public $processed = 1;
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
        ];
        
        $widget = new AccountCurrentOrdersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'account-current-orders.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<ol class="account-last-orders">#', $result);
        $this->assertRegExp('#<a href="../vendor/phpunit/phpunit/prod_1">Name 1</a>#', $result);
        $this->assertRegExp('#<br>Description 1#', $result);
        $this->assertRegExp('#<br><img src=".+" height="200" alt="">#', $result);
        $this->assertRegExp('#<br>Номер заказа:\s.+#', $result);
        $this->assertRegExp('#<br>Дата заказа:\s.+#', $result);
        $this->assertRegExp('#<br>Цвет: gray#', $result);
        $this->assertRegExp('#<br>Размер: 45#', $result);
        $this->assertRegExp('#<br>Количество: 1#', $result);
        $this->assertRegExp('#<br>Цена:\s.+\sMONEY#', $result);
        $this->assertRegExp('#<br>Общая стоимость:\s.+\sMONEY#', $result);
        $this->assertRegExp('#<br>Статус: Принят#', $result);
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
