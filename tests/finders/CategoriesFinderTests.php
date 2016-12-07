<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CategoriesFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CategoriesFixture;
use app\collections\{BaseCollection,
    CollectionInterface};
use yii\db\Query;
use app\models\CategoriesModel;

/**
 * Тестирует класс CategoriesFinder
 */
class CategoriesFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод CategoriesFinder::find
     * при отсутствии CategoriesFinder::collection
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: collection
     */
    public function testFindCollectionEmpty()
    {
        $finder = new CategoriesFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод CategoriesFinder::find
     */
    public function testFind()
    {
        $collection = new class() extends BaseCollection {};
        
        $finder = new CategoriesFinder();
        
        $reflection = new \ReflectionProperty($finder, 'collection');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $collection);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(CategoriesModel::class, $result->modelClass);
        
        $expectedQuery = "SELECT `categories`.`id`, `categories`.`name`, `categories`.`seocode`, `categories`.`active` FROM `categories`";
        
        $this->assertSame($expectedQuery, $result->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
