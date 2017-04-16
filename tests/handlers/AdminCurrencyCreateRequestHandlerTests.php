<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\UploadedFile;
use app\handlers\AdminCurrencyCreateRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\forms\CurrencyForm;

/**
 * Тестирует класс AdminCurrencyCreateRequestHandler
 */
class AdminCurrencyCreateRequestHandlerTests extends TestCase
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
        
        $this->handler = new AdminCurrencyCreateRequestHandler();
    }
    
    /**
     * Тестирует метод AdminCurrencyCreateRequestHandler::handle
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
                        'code'=>null,
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminCurrencyCreateRequestHandler::handle
     */
    public function testHandle()
    {
        $currency = \Yii::$app->db->createCommand('SELECT * FROM {{currency}}')->queryAll();
        $this->assertCount(2, $currency);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CurrencyForm'=>[
                        'code'=>'JPY',
                        'symbol'=>'&#165;',
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $currency = \Yii::$app->db->createCommand('SELECT * FROM {{currency}}')->queryAll();
        $this->assertCount(3, $currency);
    }
    
    /**
     * Тестирует метод AdminCurrencyCreateRequestHandler::handle
     * если добавляется новая базовая валюта
     */
    public function testHandleNewBase()
    {
        $currency = \Yii::$app->db->createCommand('SELECT * FROM {{currency}}')->queryAll();
        $this->assertCount(3, $currency);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CurrencyForm'=>[
                        'code'=>'AUD',
                        'symbol'=>'&#41;&#24;',
                        'main'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $currency = \Yii::$app->db->createCommand('SELECT * FROM {{currency}}')->queryAll();
        $this->assertCount(4, $currency);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
