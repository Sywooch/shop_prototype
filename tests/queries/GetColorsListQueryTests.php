<?php

namespace app\tests;

use app\queries\GetColorsListQuery;
use app\models\ProductsModel;

/**
 * Тестирует класс app\queries\GetColorsListQuery
 */
class GetColorsListQueryTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_color = 'green';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
    /**
     * Тестирует метод GetColorsListQuery::getQuery()
     * без категорий и фильтров
     */
    public function testGetQuery()
    {
        \Yii::$app->filters->clean();
        
        $currencyQuery = new GetColorsListQuery([
            'fields'=>['id', 'color'],
            'sortingField'=>'color',
            'sortingType'=>SORT_DESC
        ]);
        
        $query = "SELECT `currency`.`id`, `currency`.`currency`, `currency`.`exchange_rate`, `currency`.`main` FROM `currency` ORDER BY `currency`.`currency` DESC";
        
        $this->assertEquals($query, $currencyQuery->getQuery()->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
