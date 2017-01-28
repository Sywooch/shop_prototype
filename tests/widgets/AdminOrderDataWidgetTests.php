<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminOrderDataWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture};
use app\models\{CurrencyModel,
    PurchasesModel};
use app\forms\AdminChangeOrderForm;

/**
 * Тестирует класс AdminOrderDataWidget
 */
class AdminOrderDataWidgetTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'purchases'=>PurchasesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AdminOrderDataWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrderDataWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchase'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminOrderDataWidget::setPurchase
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchaseError()
    {
        $purchase = new class() {};
        
        $widget = new AdminOrderDataWidget();
        $widget->setPurchase($purchase);
    }
    
    /**
     * Тестирует метод AdminOrderDataWidget::setPurchase
     */
    public function testSetPurchase()
    {
        $purchase = new class() extends PurchasesModel {};
        
        $widget = new AdminOrderDataWidget();
        $widget->setPurchase($purchase);
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(PurchasesModel::class, $result);
    }
    
    /**
     * Тестирует метод AdminOrderDataWidget::setCurrency
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new AdminOrderDataWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод AdminOrderDataWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new AdminOrderDataWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод AdminOrderDataWidget::setForm
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new AdminOrderDataWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод AdminOrderDataWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends AdminChangeOrderForm {};
        
        $widget = new AdminOrderDataWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(AdminChangeOrderForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminOrderDataWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AdminOrderDataWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AdminOrderDataWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AdminOrderDataWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminOrderDataWidget::run
     * если пуст AdminOrderDataWidget::purchase
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: purchase
     */
    public function testRunEmptyPurchase()
    {
        $widget = new AdminOrderDataWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrderDataWidget::run
     * если пуст AdminOrderDataWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $mock = new class() {};
        
        $widget = new AdminOrderDataWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrderDataWidget::run
     * если пуст AdminOrderDataWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $widget = new AdminOrderDataWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrderDataWidget::run
     * если пуст AdminOrderDataWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $widget = new AdminOrderDataWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
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
     * Тестирует метод AdminOrderDataWidget::run
     */
    public function testRun()
    {
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $form = new class() extends AdminChangeOrderForm {};
        
        $purchase = new class() {
            public $id = 2;
            public $product;
            public $color;
            public $size;
            public $quantity = 1;
            public $price = 12.89;
            public $canceled = 0;
            public $shipped = 0;
            public $processed = 1;
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
                $this->name = new class() {
                    public $name = 'Name 1';
                };
                $this->surname = new class() {
                    public $surname = 'Surname 1';
                };
                $this->phone = new class() {
                    public $phone = 'Phone 1';
                };
                $this->address = new class() {
                    public $address = 'Address 1';
                };
                $this->city = new class() {
                    public $city = 'City 1';
                };
                $this->country = new class() {
                    public $country = 'Country 1';
                };
                $this->postcode = new class() {
                    public $postcode = 'Postcode 1';
                };
                $this->payment = new class() {
                    public $description = 'Payment 1';
                };
                $this->delivery = new class() {
                    public $description = 'Delivery 1';
                };
            }
        };
        
        $widget = new AdminOrderDataWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchase);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'admin-order-data.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<a href=".+">Name 1</a>#', $result);
        $this->assertRegExp('#Description 1#', $result);
        $this->assertRegExp('#<img src=".+" height="200" alt="">#', $result);
        $this->assertRegExp('#Номер заказа:\s.+#', $result);
        $this->assertRegExp('#Дата заказа:\s.+#', $result);
        $this->assertRegExp('#Цвет:\s.+#', $result);
        $this->assertRegExp('#Размер:\s.+#', $result);
        $this->assertRegExp('#Количество:\s\d+#', $result);
        $this->assertRegExp('#Цена:\s.+\sMONEY#', $result);
        $this->assertRegExp('#Общая стоимость:\s.+\sMONEY#', $result);
        $this->assertRegExp('#Покупатель: Name \d{1} Surname \d{1}#', $result);
        $this->assertRegExp('#Телефон: Phone \d{1}#', $result);
        $this->assertRegExp('#Адрес: Address \d{1}#', $result);
        $this->assertRegExp('#Город: City \d{1}#', $result);
        $this->assertRegExp('#Страна: Country \d{1}#', $result);
        $this->assertRegExp('#Почтовый код: Postcode \d{1}#', $result);
        $this->assertRegExp('#Оплата: Payment \d{1}#', $result);
        $this->assertRegExp('#Доставка: Delivery \d{1}#', $result);
        $this->assertRegExp('#Статус:\sВыполняется#', $result);
        $this->assertRegExp('#<form id="admin-order-detail-get-form-\d{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="\d{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Изменить">#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
