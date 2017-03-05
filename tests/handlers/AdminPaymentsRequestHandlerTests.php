<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminPaymentsRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс AdminPaymentsRequestHandler
 */
class AdminPaymentsRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'payments'=>PaymentsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminPaymentsRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminPaymentsRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminPaymentsRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminPaymentsRequestHandler::adminCreatePaymentWidgetConfig
     */
    public function testAdminCreatePaymentWidgetConfig()
    {
        $paymentsForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCreatePaymentWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $paymentsForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminPaymentsRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {};
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('adminPaymentsWidgetConfig', $result);
        $this->assertArrayHasKey('adminCreatePaymentWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminPaymentsWidgetConfig']);
        $this->assertInternalType('array', $result['adminCreatePaymentWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
