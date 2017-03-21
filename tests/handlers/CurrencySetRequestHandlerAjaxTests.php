<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CurrencySetRequestHandlerAjax;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\helpers\HashHelper;

/**
 * Тестирует класс CurrencySetRequestHandlerAjax
 */
class CurrencySetRequestHandlerAjaxTests extends TestCase
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
        $this->handler = new CurrencySetRequestHandlerAjax();
    }
    
    /**
     * Тестирует метод CurrencySetRequestHandlerAjax::handle
     * если пуст $request[id]
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testHandleEmptyId()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $this->handler->handle($request);
    }
    
    /**
     * Тестирует метод CurrencySetRequestHandlerAjax::handle
     * если пуст $request[url]
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: url
     */
    public function testHandleEmptyUrl()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return ($name === 'id') ? 1 : null;
            }
        };
        
        $this->handler->handle($request);
    }
    
    /**
     * Тестирует метод CurrencySetRequestHandlerAjax::handle
     */
    public function testHandle()
    {
        $key = HashHelper::createCurrencyKey();
        
        $session = \Yii::$app->session;
        $session->open();
        $session->remove($key);
        $this->assertFalse($session->has($key));
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return ($name === 'id') ? 1 : '/shop/com-2';
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertEquals('/shop/com-2', $result);
        
        $this->assertTrue($session->has($key));
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
