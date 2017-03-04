<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminDeliveriesWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\DeliveriesFixture;
use app\forms\AbstractBaseForm;
use app\models\{CurrencyInterface,
    CurrencyModel};

/**
 * Тестирует класс AdminDeliveriesWidget
 */
class AdminDeliveriesWidgetTests extends TestCase
{
    private static $dbClass;
    private $widget;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'deliveries'=>DeliveriesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->widget = new AdminDeliveriesWidget();
    }
    
    /**
     * Тестирует свойства AdminDeliveriesWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminDeliveriesWidget::class);
        
        $this->assertTrue($reflection->hasProperty('deliveries'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminDeliveriesWidget::setDeliveries
     */
    public function testSetDeliveries()
    {
        $deliveries = [new class() {}];
        
        $this->widget->setDeliveries($deliveries);
        
        $reflection = new \ReflectionProperty($this->widget, 'deliveries');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminDeliveriesWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $this->widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(CurrencyInterface::class, $result);
    }
    
    /**
     * Тестирует метод AdminDeliveriesWidget::setForm
     */
    public function testSetForm()
    {
        $deliveriesForm = new class() extends AbstractBaseForm {};
        
        $this->widget->setForm($deliveriesForm);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminDeliveriesWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $this->widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminDeliveriesWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $this->widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AdminDeliveriesWidget::run
     * если пуст AdminDeliveriesWidget::deliveries
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: deliveries
     */
    public function testRunEmptyDeliveries()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminDeliveriesWidget::run
     * если пуст AdminDeliveriesWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'deliveries');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminDeliveriesWidget::run
     * если пуст AdminDeliveriesWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'deliveries');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminDeliveriesWidget::run
     * если пуст AdminDeliveriesWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'deliveries');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminDeliveriesWidget::run
     * если пуст AdminDeliveriesWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'deliveries');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminDeliveriesWidget::run
     */
    public function testRun()
    {
        $deliveries = [
            new class() {
                public $id = 1;
                public $name = 'Name 1';
                public $description = 'Description 1';
                public $price = 15.05;
                public $active = 0;
            },
            new class() {
                public $id = 2;
                public $name = 'Name 2';
                public $description = 'Description 2';
                public $price = 25.00;
                public $active = 1;
            },
        ];
        
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'COD';
        };
        
        $form = new class() extends AbstractBaseForm{
            public $id;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'deliveries');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $deliveries);
        
        $reflection = new \ReflectionProperty($this->widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $currency);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-deliveries.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#Имя: Name [0-9]{1}#', $result);
        $this->assertRegExp('#Описание: Description [0-9]{1}#', $result);
        $this->assertRegExp('#Цена: .+#', $result);
        $this->assertRegExp('#Активен: .+#', $result);
        $this->assertRegExp('#<form id="admin-delivery-get-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="[0-9]{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Изменить">#', $result);
        $this->assertRegExp('#<form id="admin-delivery-delete-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="[0-9]{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Удалить">#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
