<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\PurchasesTodayFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;
use app\models\PurchasesModel;
use app\collections\PurchasesCollection;

/**
 * Тестирует класс PurchasesTodayFinder
 */
class PurchasesTodayFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PurchasesTodayFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchasesTodayFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод PurchasesTodayFinder::find
     * если нет удовлетворяющих условию заказов
     */
    public function testFindEmptyOrders()
    {
        $finder = new PurchasesTodayFinder();
        $result = $finder->find();
        
        $this->assertInstanceOf(PurchasesCollection::class, $result);
        $this->assertTrue($result->isEmpty());
    }
    
    /**
     * Тестирует метод PurchasesTodayFinder::find
     */
    public function testFind()
    {
        \Yii::$app->db->createCommand('UPDATE {{purchases}} SET [[received_date]]=:received_date')->bindValue(':received_date', time())->execute();
        
        $finder = new PurchasesTodayFinder();
        $result = $finder->find();
        
        $this->assertInstanceOf(PurchasesCollection::class, $result);
        $this->assertFalse($result->isEmpty());
        foreach ($result as $item) {
            $this->assertInstanceOf(PurchasesModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
