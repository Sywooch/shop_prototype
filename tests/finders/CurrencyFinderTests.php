<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CurrencyFinder;
use app\tests\sources\fixtures\CurrencyFixture;
use app\tests\DbManager;
use app\collections\{AbstractBaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\models\CurrencyModel;

/**
 * Тестирует класс CurrencyFinder
 */
class CurrencyFinderTests extends TestCase
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
     * Тестирует метод CurrencyFinder::find
     * при отсутствии CurrencyFinder::collection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: collection
     */
    public function testFindCollectionEmpty()
    {
        $finder = new CurrencyFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод CurrencyFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends AbstractBaseCollection {};
        
        $finder = new CurrencyFinder();
        
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
        
        $expectedQuery = "SELECT `currency`.`id`, `currency`.`code` FROM `currency`";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
