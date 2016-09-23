<?php

namespace app\tests\queries;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\queries\GetSubcategoryQuery;
use app\models\SubcategoryModel;

/**
 * Тестирует класс app\queries\GetSubcategoryQuery
 */
class GetSubcategoryQueryTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'subcategory'=>'app\tests\source\fixtures\SubcategoryFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод GetSubcategoryQuery::getAll()
     */
    public function testGetAll()
    {
        $subcategoryQuery = new GetSubcategoryQuery([
            'fields'=>['id', 'name', 'seocode', 'id_category', 'active'],
            'sorting'=>['name'=>SORT_DESC]
        ]);
        
        $query = $subcategoryQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `subcategory`.`id`, `subcategory`.`name`, `subcategory`.`seocode`, `subcategory`.`id_category`, `subcategory`.`active` FROM `subcategory` ORDER BY `subcategory`.`name` DESC";
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $query->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof SubcategoryModel);
    }
    
    /**
     * Тестирует метод GetSubcategoryQuery::getOne()
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->subcategory['subcategory_1'];
        
        $subcategoryQuery = new GetSubcategoryQuery([
            'fields'=>['id', 'name', 'seocode', 'id_category', 'active'],
            'extraWhere'=>['subcategory.seocode'=>$fixture['seocode']]
        ]);
        
        $query = $subcategoryQuery->getOne();
        $queryRaw = clone $query;
        
        $expectQuery = sprintf("SELECT `subcategory`.`id`, `subcategory`.`name`, `subcategory`.`seocode`, `subcategory`.`id_category`, `subcategory`.`active` FROM `subcategory` WHERE `subcategory`.`seocode`='%s'", $fixture['seocode']);
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $query->one();
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof SubcategoryModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
