<?php

namespace app\tests\updaters;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\updaters\CurrencyModelUpdater;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\models\CurrencyModel;

/**
 * Тестирует класс CurrencyModelUpdater
 */
class CurrencyModelUpdaterTests extends TestCase
{
    private $dbClass;
    private $updater;
    
    public function setUp()
    {
        $this->dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        $this->dbClass->loadFixtures();
        
        $this->updater = new CurrencyModelUpdater();
    }
    
    /**
     * Тестирует свойства CurrencyModelUpdater
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencyModelUpdater::class);
        
        $this->assertTrue($reflection->hasProperty('model'));
    }
    
    /**
     * Тестирует метод CurrencyModelUpdater::setModel
     */
    public function testSetModel()
    {
        $model = new class() extends Model {};
        
        $this->updater->setModel($model);
        
        $reflection = new \ReflectionProperty($this->updater, 'model');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->updater);
        
        $this->assertInstanceOf(Model::class, $result);
    }
    
    /**
     * Тестирует метод CurrencyModelUpdater::update
     * если пуст CurrencyModelUpdater::model
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: model
     */
    public function testRemoveEmptyModel()
    {
        $this->updater->update();
    }
    
    /**
     * Тестирует метод CurrencyModelUpdater::update
     */
    public function testUpdate()
    {
        $oldCurrency = \Yii::$app->db->createCommand('SELECT * FROM {{currency}} WHERE id=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($oldCurrency);
        
        $model = new class() {
            public $id = 1;
            public $exchange_rate = 55.1234;
            public $update_date;
            public function __construct()
            {
                $this->update_date = time();
            }
        };
        
        $reflection = new \ReflectionProperty($this->updater, 'model');
        $reflection->setAccessible(true);
        $reflection->setValue($this->updater, $model);
        
        $result = $this->updater->update();
        
        $this->assertEquals(1, $result);
        
        $newCurrency = \Yii::$app->db->createCommand('SELECT * FROM {{currency}} WHERE id=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($newCurrency);
        
        $this->assertEquals($oldCurrency['id'], $newCurrency['id']);
        $this->assertNotEquals($oldCurrency['exchange_rate'], $newCurrency['exchange_rate']);
        $this->assertNotEquals($oldCurrency['update_date'], $newCurrency['update_date']);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
