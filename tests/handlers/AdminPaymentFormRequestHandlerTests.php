<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\handlers\AdminPaymentFormRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminPaymentFormRequestHandler
 */
class AdminPaymentFormRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'payments'=>PaymentsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminPaymentFormRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminPaymentFormRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminPaymentFormRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminPaymentFormRequestHandler::adminPaymentFormWidgetConfig
     */
    public function testAdminPaymentFormWidgetConfig()
    {
        $paymentsModel = new class() extends Model {};
        $paymentForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminPaymentFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $paymentsModel, $paymentForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('payment', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(Model::class, $result['payment']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminPaymentFormRequestHandler::handle
     * если пуста форма
     * @expectedException ErrorException
     */
    public function testHandleEmptyForm()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'PaymentsForm'=>[
                        'id'=>null
                    ],
                ];
            }
        };
        
        $reqult = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminPaymentFormRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'PaymentsForm'=>[
                        'id'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
