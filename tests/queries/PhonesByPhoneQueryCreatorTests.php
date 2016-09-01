<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockModel,
    MockObject};
use app\queries\PhonesByPhoneQueryCreator;

/**
 * Тестирует класс app\queries\PhonesByPhoneQueryCreator
 */
class PhonesByPhoneQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_phone = '+380957898988';
    
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
            'tableName'=>'phones',
            'fields'=>['id', 'phone'],
            'model'=>new MockModel(['phone'=>self::$_phone])
        ]);
        
        $queryCreator = new PhonesByPhoneQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `phones`.`id`, `phones`.`phone` FROM `phones` WHERE `phones`.`phone`='" . self::$_phone . "'";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
