<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\handlers\AdminPaymentChangeRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminPaymentChangeRequestHandler
 */
class AdminPaymentChangeRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
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
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminPaymentChangeRequestHandler();
    }
    
    /**
     * Тестирует метод AdminPaymentChangeRequestHandler::adminPaymentDataWidgetConfig
     */
    public function testAdminPaymentDataWidgetConfig()
    {
        $paymentsModel = new class() extends Model {};
        $paymentForm = new class() extends AbstractBaseForm{};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminPaymentDataWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $paymentsModel, $paymentForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertInstanceOf(Model::class, $result['payment']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminPaymentChangeRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'PaymentsForm'=>[
                        'id'=>null,
                        'name'=>'Name',
                        'description'=>'Description',
                        'active'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminPaymentChangeRequestHandler::handle
     */
    public function testHandle()
    {
        $oldPayment = \Yii::$app->db->createCommand('SELECT * FROM {{payments}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($oldPayment);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'PaymentsForm'=>[
                        'id'=>1,
                        'name'=>'New name',
                        'description'=>'New description',
                        'active'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $newPayment = \Yii::$app->db->createCommand('SELECT * FROM {{payments}} WHERE [[id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($newPayment);
        
        $this->assertEquals($oldPayment['id'], $newPayment['id']);
        $this->assertNotEquals($oldPayment['name'], $newPayment['name']);
        $this->assertNotEquals($oldPayment['description'], $newPayment['description']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
