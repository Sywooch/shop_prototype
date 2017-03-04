<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminDeliveryCreateRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    DeliveriesFixture};
use app\forms\DeliveriesForm;

/**
 * Тестирует класс AdminDeliveryCreateRequestHandler
 */
class AdminDeliveryCreateRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'deliveries'=>DeliveriesFixture::class,
                'currency'=>CurrencyFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminDeliveryCreateRequestHandler();
    }
    
    /**
     * Тестирует метод AdminDeliveryCreateRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'DeliveriesForm'=>[
                        'name'=>null,
                        'description'=>'New description',
                        'price'=>12,
                        'active'=>1,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminDeliveryCreateRequestHandler::handle
     */
    public function testHandle()
    {
        $deliveries = \Yii::$app->db->createCommand('SELECT * FROM {{deliveries}}')->queryAll();
        $this->assertCount(2, $deliveries);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'DeliveriesForm'=>[
                        'name'=>'New name',
                        'description'=>'New description',
                        'price'=>12,
                        'active'=>1,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $deliveries = \Yii::$app->db->createCommand('SELECT * FROM {{deliveries}}')->queryAll();
        $this->assertCount(3, $deliveries);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
