<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject,
    MockModel};
use app\queries\ColorsInsertQueryCreator;

/**
 * Тестирует класс app\queries\ColorsInsertQueryCreator
 */
class ColorsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_color = 'gray';
    
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
            'tableName'=>'colors',
            'fields'=>['color'],
            'objectsArray'=>[
                new MockModel([
                    'color'=>self::$_color, 
                ])
            ],
        ]);
        
        $queryCreator = new ColorsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `colors` (`color`) VALUES ('" . self::$_color . "')";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
