<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminPaymentCreateRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;

/**
 * Тестирует класс AdminPaymentCreateRequestHandler
 */
class AdminPaymentCreateRequestHandlerTests extends TestCase
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
        
        $this->handler = new AdminPaymentCreateRequestHandler();
    }
    
    /**
     * Тестирует метод AdminPaymentCreateRequestHandler::handle
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
                        'name'=>null,
                        'description'=>'New description',
                        'active'=>1,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminPaymentCreateRequestHandler::handle
     */
    public function testHandle()
    {
        $payments = \Yii::$app->db->createCommand('SELECT * FROM {{payments}}')->queryAll();
        $this->assertCount(2, $payments);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'PaymentsForm'=>[
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
        
        $payments = \Yii::$app->db->createCommand('SELECT * FROM {{payments}}')->queryAll();
        $this->assertCount(3, $payments);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
