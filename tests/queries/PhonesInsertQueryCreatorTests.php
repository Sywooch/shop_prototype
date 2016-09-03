<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject,
    MockModel};
use app\queries\PhonesInsertQueryCreator;

/**
 * Тестирует класс app\queries\PhonesInsertQueryCreator
 */
class PhonesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [['+380683658978']];
    
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
            'tableName'=>'phones',
            'fields'=>['phone'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new PhonesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `phones` (`phone`) VALUES ('" . self::$_params[0][0] . "')";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
