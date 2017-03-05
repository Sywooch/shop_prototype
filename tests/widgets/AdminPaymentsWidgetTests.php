<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminPaymentsWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminPaymentsWidget
 */
class AdminPaymentsWidgetTests extends TestCase
{
    private static $dbClass;
    private $widget;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'payments'=>PaymentsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->widget = new AdminPaymentsWidget();
    }
    
    /**
     * Тестирует свойства AdminPaymentsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminPaymentsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('payments'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminPaymentsWidget::setPayments
     */
    public function testSetPayments()
    {
        $payments = [new class() {}];
        
        $this->widget->setPayments($payments);
        
        $reflection = new \ReflectionProperty($this->widget, 'payments');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminPaymentsWidget::setForm
     */
    public function testSetForm()
    {
        $paymentsForm = new class() extends AbstractBaseForm {};
        
        $this->widget->setForm($paymentsForm);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result);
    }
    
    /**
     * Тестирует метод AdminPaymentsWidget::setHeader
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
     * Тестирует метод AdminPaymentsWidget::setTemplate
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
     * Тестирует метод AdminPaymentsWidget::run
     * если пуст AdminPaymentsWidget::payments
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: payments
     */
    public function testRunEmptyPayments()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminPaymentsWidget::run
     * если пуст AdminPaymentsWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'payments');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminPaymentsWidget::run
     * если пуст AdminPaymentsWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'payments');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminPaymentsWidget::run
     * если пуст AdminPaymentsWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'payments');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, [$mock]);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminPaymentsWidget::run
     */
    public function testRun()
    {
        $payments = [
            new class() {
                public $id = 1;
                public $name = 'Name 1';
                public $description = 'Description 1';
                public $active = 0;
            },
            new class() {
                public $id = 2;
                public $name = 'Name 2';
                public $description = 'Description 2';
                public $active = 1;
            },
        ];
        
        $form = new class() extends AbstractBaseForm{
            public $id;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'payments');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $payments);
        
        $reflection = new \ReflectionProperty($this->widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'Header');
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-payments.twig');
        
        $result = $this->widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#Имя: Name [0-9]{1}#', $result);
        $this->assertRegExp('#Описание: Description [0-9]{1}#', $result);
        $this->assertRegExp('#Активен: .+#', $result);
        $this->assertRegExp('#<form id="admin-payment-get-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="[0-9]{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Изменить">#', $result);
        $this->assertRegExp('#<form id="admin-payment-delete-form-[0-9]{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="[0-9]{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Удалить">#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
