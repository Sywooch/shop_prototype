<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject,
    MockModel};
use app\queries\SizesInsertQueryCreator;

/**
 * Тестирует класс app\queries\SizesInsertQueryCreator
 */
class SizesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_size = 45;
    
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
            'objectsArray'=>[
                new MockModel([
                    'size'=>self::$_size, 
                ]),
                new MockModel([
                    'size'=>self::$_size * 1.5, 
                ])
            ],
        ]);
        
        $queryCreator = new SizesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `sizes` (`size`) VALUES (" . self::$_size . "), (" . self::$_size*1.5 . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
