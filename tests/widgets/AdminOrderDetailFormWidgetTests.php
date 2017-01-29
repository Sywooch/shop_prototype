<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminOrderDetailFormWidget;
use app\models\{CurrencyModel,
    PurchasesModel};
use app\forms\AdminChangeOrderForm;

/**
 * Тестирует класс AdminOrderDetailFormWidget
 */
class AdminOrderDetailFormWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AdminOrderDetailFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrderDetailFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchase'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('statuses'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('colors'));
        $this->assertTrue($reflection->hasProperty('sizes'));
        $this->assertTrue($reflection->hasProperty('deliveries'));
        $this->assertTrue($reflection->hasProperty('payments'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setPurchase
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchaseError()
    {
        $purchase = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setPurchase($purchase);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setPurchase
     */
    public function testSetPurchase()
    {
        $purchase = new class() extends PurchasesModel {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setPurchase($purchase);
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(PurchasesModel::class, $result);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setCurrency
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setStatuses
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetStatusesError()
    {
        $statuses = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setStatuses($statuses);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setStatuses
     */
    public function testSetStatuses()
    {
        $statuses = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setStatuses([$statuses]);
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setColors
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetColorsError()
    {
        $colors = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setColors($colors);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setColors
     */
    public function testSetColors()
    {
        $colors = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setColors([$colors]);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setSizes
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSizesError()
    {
        $sizes = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setSizes($sizes);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setSizes
     */
    public function testSetSizes()
    {
        $sizes = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setSizes([$sizes]);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setDeliveries
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetDeliveriesError()
    {
        $deliveries = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setDeliveries($deliveries);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setDeliveries
     */
    public function testSetDeliveries()
    {
        $deliveries = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setDeliveries([$deliveries]);
        
        $reflection = new \ReflectionProperty($widget, 'deliveries');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setPayments
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPaymentsError()
    {
        $payments = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setPayments($payments);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setPayments
     */
    public function testSetPayments()
    {
        $payments = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setPayments([$payments]);
        
        $reflection = new \ReflectionProperty($widget, 'payments');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setForm
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends AdminChangeOrderForm {};
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(AdminChangeOrderForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AdminOrderDetailFormWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::run
     * если пуст AdminOrderDetailFormWidget::purchase
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: purchase
     */
    public function testRunEmptyPurchase()
    {
        $widget = new AdminOrderDetailFormWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::run
     * если пуст AdminOrderDetailFormWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $mock = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::run
     * если пуст AdminOrderDetailFormWidget::statuses
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: statuses
     */
    public function testRunEmptyStatuses()
    {
        $mock = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::run
     * если пуст AdminOrderDetailFormWidget::colors
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: colors
     */
    public function testRunEmptyColors()
    {
        $mock = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::run
     * если пуст AdminOrderDetailFormWidget::sizes
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sizes
     */
    public function testRunEmptySizes()
    {
        $mock = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::run
     * если пуст AdminOrderDetailFormWidget::deliveries
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: deliveries
     */
    public function testRunEmptyDeliveries()
    {
        $mock = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::run
     * если пуст AdminOrderDetailFormWidget::payments
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: payments
     */
    public function testRunEmptyPayments()
    {
        $mock = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'deliveries');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::run
     * если пуст AdminOrderDetailFormWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'deliveries');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'payments');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::run
     * если пуст AdminOrderDetailFormWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $widget = new AdminOrderDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'deliveries');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'payments');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormWidget::run
     */
    public function testRun()
    {
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $statuses = ['shipped'=>'Shipped', 'canceled'=>'Canceled', 'processed'=>'Processed', 'received'=>'Received'];
        
        $form = new class() extends AdminChangeOrderForm {};
        
        $colors = [1=>'black', 2=>'gray'];
        $sizes = [1=>50, 2=>45.5];
        $deliveries = [1=>'Delivery 1', 2=>'Delivery 2'];
        $payments = [1=>'Payment 1', 2=>'Payment 2'];
        
        $purchase = new class() {
            public $id = 2;
            public $product;
            public $quantity = 1;
            public $price = 12.89;
            public $canceled = 0;
            public $shipped = 0;
            public $processed = 0;
            public $received = 1;
            public $received_date = 1459112400;
            public $id_color = 1;
            public $id_size = 1;
            public $id_delivery = 1;
            public $id_payment = 1;
            public $email;
            public function __construct()
            {
                $this->product = new class() {
                    public $seocode = 'prod_1';
                    public $name = 'Name 1';
                    public $short_description = 'Description 1';
                    public $images = 'test';
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
                $this->email = new class() {
                    public $email = 'mail@mail.com';
                };
            }
        };
        
        $widget = new AdminOrderDetailFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchase');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchase);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'statuses');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $statuses);
        
        $reflection = new \ReflectionProperty($widget, 'colors');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $colors);
        
        $reflection = new \ReflectionProperty($widget, 'sizes');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $sizes);
        
        $reflection = new \ReflectionProperty($widget, 'deliveries');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $deliveries);
        
        $reflection = new \ReflectionProperty($widget, 'payments');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $payments);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'admin-order-detail-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<a href=".+">Name 1</a>#', $result);
        $this->assertRegExp('#Description 1#', $result);
        $this->assertRegExp('#<img src=".+" height="200" alt="">#', $result);
        $this->assertRegExp('#Номер заказа: 2#', $result);
        $this->assertRegExp('#Дата заказа: 28 марта 2016 г.#', $result);
        $this->assertRegExp('#Цена: 26,94 MONEY#', $result);
        $this->assertRegExp('#Email: mail@mail.com#', $result);
        $this->assertRegExp('#<form id="admin-order-detail-send-form-\d" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="2">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[name\]" value="Name 1">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[surname\]" value="Surname 1">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[phone\]" value="Phone 1">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[address\]" value="Address 1">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[city\]" value="City 1">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[country\]" value="Country 1">#', $result);
        $this->assertRegExp('#<input type="text" id=".+" class="form-control" name=".+\[postcode\]" value="Postcode 1">#', $result);
        $this->assertRegExp('#<input type="number" id=".+" class="form-control" name=".+\[quantity\]" value="1" step="1" min="1">#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[id_color\]">#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[id_size\]">#', $result);
        $this->assertRegExp('#<label><input type="radio" name=".+\[id_delivery\]" value="1" checked> Delivery \d</label>#', $result);
        $this->assertRegExp('#<label><input type="radio" name=".+\[id_payment\]" value="1" checked> Payment \d</label>#', $result);
        $this->assertRegExp('#<select id=".+" class="form-control" name=".+\[status\]">#', $result);
        $this->assertRegExp('#<option value=".+">.+</option>#', $result);
        $this->assertRegExp('#<input type="submit" name="send" value="Сохранить">#', $result);
        $this->assertRegExp('#<input type="submit" name="cancel" value="Отменить">#', $result);
    }
}
