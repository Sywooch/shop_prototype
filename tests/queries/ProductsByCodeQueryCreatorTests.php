<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockModel,
    MockObject};
use app\queries\ProductsByCodeQueryCreator;

/**
 * Тестирует класс app\queries\ProductsByCodeQueryCreator
 */
class ProductsByCodeQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_code = '13HU-2343';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products',
            'fields'=>['id', 'date', 'code', 'name', 'description', 'price', 'images', 'id_categories', 'id_subcategory'],
            'model'=>new MockModel(['code'=>self::$_code])
        ]);
        
        $queryCreator = new ProductsByCodeQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `products`.`id`, `products`.`date`, `products`.`code`, `products`.`name`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory` FROM `products` WHERE `products`.`code`='" . self::$_code . "'";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
