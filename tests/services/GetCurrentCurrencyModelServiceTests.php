<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
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
    private $service;
    
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
        
        $this->service = new GetCurrentCurrencyModelService();
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
     */
    public function testSetKey()
    {
        $this->service->setKey('key');
        
        $reflection = new \ReflectionProperty($this->service, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertEquals('key', $result);
    }
    
    /**
     * Тестирует метод GetCurrentCurrencyModelService::updateCurrency
     */
    public function testUpdateCurrency()
    {
        $currencyModel = new CurrencyModel();
        $currencyModel->update_date = time();
        $currencyModel->code = 'USD';
        
        $reflection = new \ReflectionMethod($this->service, 'updateCurrency');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->service, $currencyModel);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод GetCurrentCurrencyModelService::get
     * если пуст GetCurrentCurrencyModelService::key
     * @expectedException ErrorException
     * Отсутствуют необходимые данные: key
     */
    public function testGetEmptyKey()
    {
        $this->service->get();
    }
    
    /**
     * Тестирует метод GetCurrentCurrencyModelService::get
     * если сессия пуста
     */
    public function testGetEmptySession()
    {
        $key = HashHelper::createCurrencyKey();
        
        self::$session->open();
        self::$session->remove($key);
        $this->assertFalse(self::$session->has($key));
        
        $reflection = new \ReflectionProperty($this->service, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $key);
        
        $result = $this->service->get();
        
        $this->assertInstanceOf(Model::class, $result);
        
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
        
        $reflection = new \ReflectionProperty($this->service, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $key);
        
        $result = $this->service->get();
        
        $this->assertInstanceOf(Model::class, $result);
        
        self::$session->remove($key);
        self::$session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
