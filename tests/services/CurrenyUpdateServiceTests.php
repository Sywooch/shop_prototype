<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\services\CurrenyUpdateService;
use app\models\CurrencyModel;
use app\helpers\HashHelper;
use app\tests\sources\fixtures\CurrencyFixture;
use app\tests\DbManager;

/**
 * Тестирует класс CurrenyUpdateService
 */
class CurrenyUpdateServiceTests extends TestCase
{
    private static $dbClass;
    private $service;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->service = new CurrenyUpdateService();
    }
    
    /**
     * Тестирует свойства CurrenyUpdateService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrenyUpdateService::class);
        
        $this->assertTrue($reflection->hasProperty('updateCurrencyModel'));
    }
    
    /**
     * Тестирует метод CurrenyUpdateService::setUpdateCurrencyModel
     */
    public function testSetUpdateCurrencyModel()
    {
        $updateCurrencyModel = new class extends Model {};
        
        $this->service->setUpdateCurrencyModel($updateCurrencyModel);
        
        $reflection = new \ReflectionProperty($this->service, 'updateCurrencyModel');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->service);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод CurrenyUpdateService::get
     * если пуст CurrenyUpdateService::updateCurrencyModel
     * @expectedException ErrorException
     * Отсутствуют необходимые данные: updateCurrencyModel
     */
    public function testGetEmptyUpdateCurrencyModel()
    {
        $this->service->get();
    }
    
    /**
     * Тестирует метод CurrenyUpdateService::get
     */
    public function testGet()
    {
        $updateCurrencyModel = CurrencyModel::findOne(2);
        $oldExchange_rate = $updateCurrencyModel->exchange_rate;
        $oldUpdate_date = $updateCurrencyModel->update_date;
        
        $reflection = new \ReflectionProperty($this->service, 'updateCurrencyModel');
        $reflection->setAccessible(true);
        $reflection->setValue($this->service, $updateCurrencyModel);
        
        $result = $this->service->get();
        
        $this->assertInstanceOf(Model::class, $result);
        
        $this->assertNotEquals($oldExchange_rate, $result->exchange_rate);
        $this->assertNotEquals($oldUpdate_date, $result->update_date);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
