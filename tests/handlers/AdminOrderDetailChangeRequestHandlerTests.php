<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminOrderDetailChangeRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture};

/**
 * Тестирует класс AdminOrderDetailChangeRequestHandler
 */
class AdminOrderDetailChangeRequestHandlerTests extends TestCase
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
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод AdminOrderDetailChangeRequestHandler::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new AdminOrderDetailChangeRequestHandler();
        $service->handle();
    }
    
    /**
     * Тестирует метод AdminOrderDetailChangeRequestHandler::handle
     * если в запросе ошибки
     */
    /*public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminProductForm'=>[
                        'id'=>null
                    ],
                ];
            }
        };
        
        $service = new AdminOrderDetailChangeRequestHandler();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
    }*/
    
    /**
     * Тестирует метод AdminOrderDetailChangeRequestHandler::handle
     */
    /*public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminProductForm'=>[
                        'id'=>1
                    ],
                ];
            }
        };
        
        $service = new AdminOrderDetailChangeRequestHandler();
        $result = $service->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }*/
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
