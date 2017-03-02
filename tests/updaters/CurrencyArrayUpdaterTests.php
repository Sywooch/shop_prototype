<?php

namespace app\tests\updaters;

use PHPUnit\Framework\TestCase;
use yii\helpers\ArrayHelper;
use app\updaters\CurrencyArrayUpdater;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;

/**
 * Тестирует класс CurrencyArrayUpdater
 */
class CurrencyArrayUpdaterTests extends TestCase
{
    private static $dbClass;
    private $updater;
    
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
        $this->updater = new CurrencyArrayUpdater();
    }
    
    /**
     * Тестирует свойства CurrencyArrayUpdater
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencyArrayUpdater::class);
        
        $this->assertTrue($reflection->hasProperty('models'));
    }
    
    /**
     * Тестирует метод CurrencyArrayUpdater::setModels
     */
    public function testSetModels()
    {
        $models = [new class() {}];
        
        $this->updater->setModels($models);
        
        $reflection = new \ReflectionProperty($this->updater, 'models');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->updater);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод CurrencyArrayUpdater::update
     * если пуст CurrencyArrayUpdater::models
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: models
     */
    public function testSaveEmptyModels()
    {
        $this->updater->update();
    }
    
    /**
     * Тестирует метод CurrencyArrayUpdater::update
     */
    public function testUpdate()
    {
        $models = [
            new class() {
                public $id = 1;
                public $code = 'NEW';
                public $main = 0;
                public $exchange_rate = 0.056;
                public $update_date;
                public function __construct()
                {
                    $this->update_date = time();
                }
            },
            new class() {
                public $id = 2;
                public $code = 'NEW';
                public $main = 0;
                public $exchange_rate = 0.056;
                public $update_date;
                public function __construct()
                {
                    $this->update_date = time();
                }
            }
        ];
        
        $oldCurrency = \Yii::$app->db->createCommand('SELECT * FROM {{currency}}')->queryAll();
        $this->assertCount(2, $oldCurrency);
        
        $reflection = new \ReflectionProperty($this->updater, 'models');
        $reflection->setAccessible(true);
        $reflection->setValue($this->updater, $models);
        
        $result = $this->updater->update();
        
        $this->assertEquals(4, $result);
        
        $newCurrency = \Yii::$app->db->createCommand('SELECT * FROM {{currency}}')->queryAll();
        $this->assertCount(2, $newCurrency);
        
        $oldCurrency = ArrayHelper::index($oldCurrency, 'id');
        $newCurrency = ArrayHelper::index($newCurrency, 'id');
        
        $this->assertEquals($oldCurrency[1]['code'], $newCurrency[1]['code']);
        $this->assertEquals($oldCurrency[2]['code'], $newCurrency[2]['code']);
        $this->assertNotEquals($oldCurrency[1]['exchange_rate'], $newCurrency[1]['exchange_rate']);
        $this->assertNotEquals($oldCurrency[2]['exchange_rate'], $newCurrency[2]['exchange_rate']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
