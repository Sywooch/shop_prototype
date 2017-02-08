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
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('currencyModel'));
    }
    
    /**
     * Тестирует метод GetCurrentCurrencyModelService::setKey
     * передаю неверный параметр
     * @expectedException TypeError
     */
    public function testSetKeyError()
    {
        $service = new GetCurrentCurrencyModelService();
        $service->setKey([]);
    }
    
    /**
     * Тестирует метод GetCurrentCurrencyModelService::setKey
     */
    public function testSetKey()
    {
        $service = new GetCurrentCurrencyModelService();
        $service->setKey('key');
        
        $reflection = new \ReflectionProperty($service, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($service);
        
        $this->assertEquals('key', $result);
    }
    
    /**
     * Тестирует метод GetCurrentCurrencyModelService::get
     * если пуст GetCurrentCurrencyModelService::key
     * @expectedException ErrorException
     * Отсутствуют необходимые данные: key
     */
    public function testGetEmptyKey()
    {
        $service = new GetCurrentCurrencyModelService();
        $service->get();
    }
    
    /**
     * Тестирует метод GetCurrentCurrencyModelService::get
     * если сессия пуста
     */
    public function testGetEmptySession()
    {
        $key = HashHelper::createCurrencyKey();
        
        self::$session->open();
        $this->assertFalse(self::$session->has($key));
        
        $service = new GetCurrentCurrencyModelService();
        
        $reflection = new \ReflectionProperty($service, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($service, $key);
        
        $result = $service->get();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
        
        self::$session->close();
    }
    
    /**
     * Тестирует метод GetCurrentCurrencyModelService::get
     * если сессия не пуста
     * @depends testGetEmptySession
     */
    public function testGetMSDB()
    {
        $key = HashHelper::createCurrencyKey();
        
        $this->assertTrue(self::$session->has($key));
        
        $service = new GetCurrentCurrencyModelService();
        
        $reflection = new \ReflectionProperty($service, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($service, $key);
        
        $result = $service->get();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
        
        self::$session->remove($key);
        self::$session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
