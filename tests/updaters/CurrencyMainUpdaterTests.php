<?php

namespace app\tests\updaters;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\updaters\CurrencyMainUpdater;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\models\CurrencyModel;

/**
 * Тестирует класс CurrencyMainUpdater
 */
class CurrencyMainUpdaterTests extends TestCase
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
        
        $this->updater = new CurrencyMainUpdater();
    }
    
    /**
     * Тестирует метод CurrencyMainUpdater::update
     */
    public function testUpdate()
    {
        $oldCurrency = \Yii::$app->db->createCommand('SELECT * FROM {{currency}} WHERE main=:main')->bindValue(':main', 1)->queryOne();
        $this->assertNotEmpty($oldCurrency);
        
        $result = $this->updater->update();
        
        $this->assertEquals(1, $result);
        
        $newCurrency = \Yii::$app->db->createCommand('SELECT * FROM {{currency}} WHERE main=:main')->bindValue(':main', 1)->queryOne();
        $this->assertEmpty($newCurrency);
    }
    
    public function tearDown()
    {
        $this->dbClass->unloadFixtures();
    }
}
