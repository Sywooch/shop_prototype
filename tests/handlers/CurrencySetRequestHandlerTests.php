<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CurrencySetRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\helpers\HashHelper;

/**
 * Тестирует класс CurrencySetRequestHandler
 */
class CurrencySetRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->handler = new CurrencySetRequestHandler();
    }
    
    /**
     * Тестирует метод CurrencySetRequestHandler::handle
     * если POST пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: POST
     */
    public function testHandleEmptyPost()
    {
        $request = new class() {
            public $isPost = true;
            public function post($name = null, $defaultValue = null)
            {
                return [];
            }
        };
        
        $this->handler->handle($request);
    }
    
    /**
     * Тестирует метод CurrencySetRequestHandler::handle
     * если данные не валидны
     * @expectedException ErrorException
     * @expectedExceptionMessage Необходимо заполнить «Url»
     */
    public function testHandleInvalidData()
    {
        $request = new class() {
            public $isPost = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'ChangeCurrencyForm'=>[
                        'id'=>1,
                        'url'=>null
                    ]
                ];
            }
        };
        
        $this->handler->handle($request);
    }
    
    /**
     * Тестирует метод CurrencySetRequestHandler::handle
     */
    public function testHandle()
    {
        $key = HashHelper::createCurrencyKey();
        
        $session = \Yii::$app->session;
        $session->open();
        $session->remove($key);
        $this->assertFalse($session->has($key));
        
        $request = new class() {
            public $isPost = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'ChangeCurrencyForm'=>[
                        'id'=>1,
                        'url'=>'https://shop.com',
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertEquals('https://shop.com', $result);
        
        $this->assertTrue($session->has($key));
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
