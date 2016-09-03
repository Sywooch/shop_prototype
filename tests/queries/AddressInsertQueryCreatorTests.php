<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\AddressInsertQueryCreator;

/**
 * Тестирует класс app\queries\AddressInsertQueryCreator
 */
class AddressInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [['Some Address', 'Some city', 'Some country', '09100']];
    
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
            'tableName'=>'address',
            'fields'=>['address', 'city', 'country', 'postcode'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new AddressInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `address` (`address`, `city`, `country`, `postcode`) VALUES ('" . implode("', '", self::$_params[0]) . "')";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
