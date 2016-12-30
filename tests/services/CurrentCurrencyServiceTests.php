<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CurrentCurrencyService;
use app\models\CurrencyModel;
use app\helpers\HashHelper;
use app\tests\sources\fixtures\CurrencyFixture;
use app\tests\DbManager;

/**
 * Тестирует класс CurrentCurrencyService
 */
class CurrentCurrencyServiceTests extends TestCase
{
    private static $dbClass;
    private static $session;
    private static $key;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        self::$key = HashHelper::createCurrencyKey();
        self::$session = \Yii::$app->session;
        self::$session->open();
    }
    
    /**
     * Тестирует метод CurrentCurrencyService::handle
     * если сессия пуста
     */
    public function testHandleEmptySession()
    {
        $this->assertFalse(self::$session->has(self::$key));
        
        $service = new CurrentCurrencyService();
        $result = $service->handle();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод CurrentCurrencyService::handle
     * если сессия не пуста
     * @depends testHandleEmptySession
     */
    public function testHandleMSDB()
    {
        $this->assertTrue(self::$session->has(self::$key));
        
        $service = new CurrentCurrencyService();
        $result = $service->handle();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
        
        self::$session->remove(self::$key);
        self::$session->close();
    }
}
