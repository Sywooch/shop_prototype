<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCurrentCurrencyModelService;
use app\models\CurrencyModel;
use app\helpers\HashHelper;
use app\tests\sources\fixtures\CurrencyFixture;
use app\tests\DbManager;

/**
 * Тестирует класс GetCurrentCurrencyModelService
 */
class GetCurrentCurrencyModelServiceTests extends TestCase
{
    private static $dbClass;
    private static $session;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        self::$session = \Yii::$app->session;
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства GetCurrentCurrencyModelService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCurrentCurrencyModelService::class);
        
        $this->assertTrue($reflection->hasProperty('currencyModel'));
    }
    
    /**
     * Тестирует метод GetCurrentCurrencyModelService::handle
     * если сессия пуста
     */
    public function testHandleEmptySession()
    {
        $key = HashHelper::createCurrencyKey();
        self::$session->open();
        self::$session->remove($key);
        
        $this->assertFalse(self::$session->has($key));
        
        $service = new GetCurrentCurrencyModelService();
        $result = $service->handle();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
        
        self::$session->close();
    }
    
    /**
     * Тестирует метод GetCurrentCurrencyModelService::handle
     * если сессия не пуста
     * @depends testHandleEmptySession
     */
    public function testHandleMSDB()
    {
        $key = HashHelper::createCurrencyKey();
        
        $this->assertTrue(self::$session->has($key));
        
        $service = new GetCurrentCurrencyModelService();
        $result = $service->handle();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
        
        self::$session->remove($key);
        self::$session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
