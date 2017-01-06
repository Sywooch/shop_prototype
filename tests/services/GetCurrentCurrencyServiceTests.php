<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCurrentCurrencyService;
use app\models\CurrencyModel;
use app\helpers\HashHelper;
use app\tests\sources\fixtures\CurrencyFixture;
use app\tests\DbManager;

/**
 * Тестирует класс GetCurrentCurrencyService
 */
class GetCurrentCurrencyServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetCurrentCurrencyService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCurrentCurrencyService::class);
        
        $this->assertTrue($reflection->hasProperty('currencyModel'));
    }
    
    /**
     * Тестирует метод GetCurrentCurrencyService::handle
     * если сессия пуста
     */
    public function testHandleEmptySession()
    {
        $key = HashHelper::createCurrencyKey();
        $session = \Yii::$app->session;
        $session->open();
        $session->remove($key);
        
        $this->assertFalse($session->has($key));
        
        $service = new GetCurrentCurrencyService();
        $result = $service->handle();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод GetCurrentCurrencyService::handle
     * если сессия не пуста
     * @depends testHandleEmptySession
     */
    public function testHandleMSDB()
    {
        $key = HashHelper::createCurrencyKey();
        $session = \Yii::$app->session;
        $session->open();
        
        $this->assertTrue($session->has($key));
        
        $service = new GetCurrentCurrencyService();
        $result = $service->handle();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
