<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CurrencyIdFinder;
use app\tests\sources\fixtures\CurrencyFixture;
use app\tests\DbManager;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\models\CurrencyModel;

/**
 * Тестирует класс CurrencyIdFinder
 */
class CurrencyIdFinderTests extends TestCase
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
     * Тестирует свойства CurrencyIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencyIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
    }
    
    /**
     * Тестирует метод CurrencyIdFinder::rules
     */
    public function testRules()
    {
        $finder = new CurrencyIdFinder();
        $finder->validate();
        
        $this->assertNotEmpty($finder->errors);
        $this->assertCount(1, $finder->errors);
        $this->assertArrayHasKey('id', $finder->errors);
        
        $finder = new CurrencyIdFinder();
        $finder->attributes = ['id'=>1];
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
     /**
     * Тестирует метод CurrencyIdFinder::find
     * если CurrencyIdFinder::collection пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: collection
     */
    public function testFindEmptyCollection()
    {
        $finder = new CurrencyIdFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод CurrencyIdFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new CurrencyIdFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $reflection = new \ReflectionProperty($finder, 'id');
        $reflection->setValue($finder, 1);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
        
        $reflection = new \ReflectionProperty($result, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($result);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(CurrencyModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT `currency`.`id`, `currency`.`code`, `currency`.`exchange_rate` FROM `currency` WHERE `currency`.`id`=1";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
