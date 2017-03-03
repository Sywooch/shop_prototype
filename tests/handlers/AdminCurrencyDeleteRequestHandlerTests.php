<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminCurrencyDeleteRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;

/**
 * Тестирует класс AdminCurrencyDeleteRequestHandler
 */
class AdminCurrencyDeleteRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminCurrencyDeleteRequestHandler();
    }
    
    /**
     * Тестирует метод AdminCurrencyDeleteRequestHandler::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CurrencyForm'=>[
                        'id'=>null
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminCurrencyDeleteRequestHandler::handle
     */
    public function testHandle()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{currency}}')->queryAll();
        $this->assertCount(2, $result);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CurrencyForm'=>[
                        'id'=>2,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{currency}}')->queryAll();
        $this->assertCount(1, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
