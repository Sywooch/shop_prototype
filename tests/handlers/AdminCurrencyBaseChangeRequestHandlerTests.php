<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\UploadedFile;
use app\handlers\AdminCurrencyBaseChangeRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\forms\CurrencyForm;

/**
 * Тестирует класс AdminCurrencyBaseChangeRequestHandler
 */
class AdminCurrencyBaseChangeRequestHandlerTests extends TestCase
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
        
        $this->handler = new AdminCurrencyBaseChangeRequestHandler();
    }
    
    /**
     * Тестирует метод AdminCurrencyBaseChangeRequestHandler::handle
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
                        'id'=>1,
                        'main'=>null
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminCurrencyBaseChangeRequestHandler::handle
     */
    public function testHandle()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{currency}} WHERE [[main]]=1')->queryAll();
        $this->assertCount(1, $result);
        $oldBase = $result[0];
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'CurrencyForm'=>[
                        'id'=>2,
                        'main'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{currency}} WHERE [[main]]=1')->queryAll();
        $this->assertCount(1, $result);
        $newBase = $result[0];
        
        $this->assertNotEquals($oldBase['id'], $newBase['id']);
        $this->assertEquals(1, (int) $newBase['exchange_rate']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
