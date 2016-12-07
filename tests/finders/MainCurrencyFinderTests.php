<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\MainCurrencyFinder;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\models\CurrencyModel;

/**
 * Тестирует класс MainCurrencyFinder
 */
class MainCurrencyFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод MainCurrencyFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new MainCurrencyFinder();
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(CurrencyModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT `currency`.`id`, `currency`.`code`, `currency`.`exchange_rate`, `currency`.`main` FROM `currency` WHERE `currency`.`main`=TRUE";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
