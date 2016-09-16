<?php

namespace app\tests\queries;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\source\fixtures\CategoriesFixture;
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
                'categories'=>CategoriesFixture::className(),
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод GetCategoriesQuery::getAll()
     * без категорий и фильтров
     */
    public function testGetAll()
    {
        $_GET = [];
        \Yii::$app->filters->clean();
        
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
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
