<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\SizesUpdateQueryCreator;

/**
 * Тестирует класс app\queries\SizesUpdateQueryCreator
 */
class SizesUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[1, 46]];
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new SizesUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `sizes` (`id`, `size`) VALUES (" . implode(', ', self::$_params[0]) . ") ON DUPLICATE KEY UPDATE `size`=VALUES(`size`)";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
