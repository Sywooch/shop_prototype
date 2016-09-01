<?php

namespace app\queries;

use app\tests\{DbManager,
    MockModel,
    MockObject};
use app\queries\AddressByAddressQueryCreator;

/**
 * Тестирует класс app\queries\AddressByAddressQueryCreator
 */
class AddressByAddressQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_address = 'Some address';
    private static $_city = 'Some city';
    private static $_country = 'Some country';
    private static $_postcode = '34532';
    
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
            'fields'=>['id', 'address', 'city', 'country', 'postcode'],
            'model'=>new MockModel([
                'address'=>self::$_address,
                'city'=>self::$_city,
                'country'=>self::$_country,
                'postcode'=>self::$_postcode
            ])
        ]);
        
        $queryCreator = new AddressByAddressQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `address`.`id`, `address`.`address`, `address`.`city`, `address`.`country`, `address`.`postcode` FROM `address` WHERE (`address`.`address`='" . self::$_address . "') AND (`address`.`city`='" . self::$_city . "') AND (`address`.`country`='" . self::$_country . "') AND (`address`.`postcode`='" .  self::$_postcode . "')";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL запроса
     * при условии отсутствия country, postcode
     */
    public function testGetInsertQueryWithoutPostcode()
    {
        $mockObject = new MockObject([
            'tableName'=>'address',
            'fields'=>['id', 'address', 'city', 'country', 'postcode'],
            'model'=>new MockModel([
                'address'=>self::$_address,
                'city'=>self::$_city,
            ])
        ]);
        
        $queryCreator = new AddressByAddressQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `address`.`id`, `address`.`address`, `address`.`city`, `address`.`country`, `address`.`postcode` FROM `address` WHERE (`address`.`address`='" . self::$_address . "') AND (`address`.`city`='" . self::$_city . "')";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
