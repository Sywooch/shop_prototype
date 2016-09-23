<?php

namespace app\tests\queries;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\queries\GetCategoriesQuery;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\queries\GetCategoriesQuery
 */
class GetCategoriesQueryTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>'app\tests\source\fixtures\CategoriesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод GetCategoriesQuery::getAll()
     */
    public function testGetAll()
    {
        $categoriesQuery = new GetCategoriesQuery([
            'fields'=>['id', 'name', 'seocode'],
            'sorting'=>['name'=>SORT_DESC]
        ]);
        
        $query = $categoriesQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `categories`.`id`, `categories`.`name`, `categories`.`seocode` FROM `categories` ORDER BY `categories`.`name` DESC";
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $query->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof CategoriesModel);
    }
    
    /**
     * Тестирует метод GetCategoriesQuery::getOne()
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        
        $categoriesQuery = new GetCategoriesQuery([
            'fields'=>['id', 'name', 'seocode'],
            'extraWhere'=>['categories.seocode'=>$fixture['seocode']]
        ]);
        
        $query = $categoriesQuery->getOne();
        $queryRaw = clone $query;
        
        $expectQuery = sprintf("SELECT `categories`.`id`, `categories`.`name`, `categories`.`seocode` FROM `categories` WHERE `categories`.`seocode`='%s'", $fixture['seocode']);
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $query->one();
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof CategoriesModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
