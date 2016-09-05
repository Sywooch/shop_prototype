<?php

namespace app\tests;

use app\queries\GetCategoriesListQuery;
use app\models\ProductsModel;

/**
 * Тестирует класс app\queries\GetCategoriesListQuery
 */
class GetCategoriesListQueryTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
    /**
     * Тестирует метод GetCategoriesListQuery::getQuery()
     * без категорий и фильтров
     */
    public function testGetQuery()
    {
        $productsQuery = new GetCategoriesListQuery([
            'className'=>ProductsModel::className(),
            'fields'=>['id', 'name', 'seocode'],
            'sortingField'=>'name',
            'sortingType'=>SORT_DESC
        ]);
        
        $query = "SELECT `products`.`id`, `products`.`name`, `products`.`seocode` FROM `products` ORDER BY `products`.`name` DESC";
        
        $this->assertEquals($query, $productsQuery->getQuery()->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
