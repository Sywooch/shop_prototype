<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\widgets\AdminPaymentDataWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;
use app\forms\AbstractBaseForm;
use app\models\{CurrencyInterface,
    CurrencyModel};

/**
 * Тестирует класс AdminPaymentDataWidget
 */
class AdminPaymentDataWidgetTests extends TestCase
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
        $this->widget = new AdminPaymentDataWidget();
    }
    
    /**
     * Тестирует свойства AdminPaymentDataWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminPaymentDataWidget::class);
        
        $this->assertTrue($reflection->hasProperty('payment'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AdminPaymentDataWidget::setPayment
     */
    public function testSetPayment()
    {
        $payment = new class() extends Model {};
        
        $this->widget->setPayment($payment);
        
        $reflection = new \ReflectionProperty($this->widget, 'payment');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->widget);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод AdminPaymentDataWidget::setForm
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
     * Тестирует метод AdminPaymentDataWidget::setTemplate
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
     * Тестирует метод AdminPaymentDataWidget::run
     * если пуст AdminPaymentDataWidget::payment
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: payment
     */
    public function testRunEmptyPayment()
    {
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminPaymentDataWidget::run
     * если пуст AdminPaymentDataWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'payment');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminPaymentDataWidget::run
     * если пуст AdminPaymentDataWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $reflection = new \ReflectionProperty($this->widget, 'payment');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $mock);
        
        $this->widget->run();
    }
    
    /**
     * Тестирует метод AdminPaymentDataWidget::run
     */
    public function testRun()
    {
        $payment = new class() {
            public $id = 1;
            public $name = 'Name 1';
            public $description = 'Description 1';
            public $active = 1;
        };
        
        $form = new class() extends AbstractBaseForm{
            public $id;
        };
        
        $reflection = new \ReflectionProperty($this->widget, 'payment');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $payment);
        
        $reflection = new \ReflectionProperty($this->widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, $form);
        
        $reflection = new \ReflectionProperty($this->widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($this->widget, 'admin-payment-data.twig');
        
        $result = $this->widget->run();
        
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
