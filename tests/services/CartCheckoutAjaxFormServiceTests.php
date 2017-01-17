<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CartCheckoutAjaxFormService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    DeliveriesFixture,
    PaymentsFixture};

/**
 * Тестирует класс CartCheckoutAjaxFormService
 */
class CartCheckoutAjaxFormServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'deliveries'=>DeliveriesFixture::class,
                'payments'=>PaymentsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод CartCheckoutAjaxFormService::handle
     */
    public function testHandle()
    {
        \Yii::$app->user->logout();
        
        $request = new class() {
            public $isAjax = true;
        };
        
        $service = new CartCheckoutAjaxFormService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
