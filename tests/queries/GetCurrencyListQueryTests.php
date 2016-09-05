<?php

namespace app\tests;

use app\queries\GetCurrencyListQuery;
use app\models\ProductsModel;

/**
 * Тестирует класс app\queries\GetCurrencyListQuery
 */
class GetCurrencyListQueryTests extends \PHPUnit_Framework_TestCase
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
     * Тестирует метод GetCurrencyListQuery::getQuery()
     * без категорий и фильтров
     */
    public function testGetQuery()
    {
        \Yii::$app->filters->clean();
        
        $currencyQuery = new GetCurrencyListQuery([
            'fields'=>['id', 'currency', 'exchange_rate', 'main'],
            'sortingField'=>'currency',
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
