<?php

namespace app\tests;

use app\queries\GetCategoriesQuery;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\queries\GetCategoriesQuery
 */
class GetCategoriesQueryTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 12;
    private static $_name = 'name';
    private static $_categorySeocode = 'mensfootwear';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
    }
    
    /**
     * Тестирует метод GetCategoriesQuery::getAll()
     * без категорий и фильтров
     */
    public function testGetQuery()
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
        self::$_dbClass->deleteDb();
    }
}
