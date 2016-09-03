<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\SizesInsertQueryCreator;

/**
 * Тестирует класс app\queries\SizesInsertQueryCreator
 */
class SizesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[45], [23]];
    
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
            'fields'=>['size'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new SizesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `sizes` (`size`) VALUES (" . self::$_params[0][0] . "), (" . self::$_params[1][0] . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
