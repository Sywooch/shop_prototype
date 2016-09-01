<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject,
    MockModel};
use app\queries\AddressInsertQueryCreator;

/**
 * Тестирует класс app\queries\AddressInsertQueryCreator
 */
class AddressInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_address = 'Some Address';
    private static $_city = 'Some city';
    private static $_country = 'Some country';
    private static $_postcode = '5687';
    
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
            'objectsArray'=>[
                new MockModel([
                    'address'=>self::$_address,
                    'city'=>self::$_city,
                    'country'=>self::$_country,
                    'postcode'=>self::$_postcode
                ]),
            ],
        ]);
        
        $queryCreator = new AddressInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `address` (`address`, `city`, `country`, `postcode`) VALUES ('" . self::$_address . "', '" . self::$_city . "', '" . self::$_country . "', '" .self::$_postcode . "')";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
