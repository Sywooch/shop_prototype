<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject,
    MockModel};
use app\queries\EmailsInsertQueryCreator;

/**
 * Тестирует класс app\queries\EmailsInsertQueryCreator
 */
class EmailsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_email = 'some@some.com';
    
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
            'tableName'=>'emails',
            'fields'=>['email'],
            'objectsArray'=>[
                new MockModel(['email'=>self::$_email])
            ],
        ]);
        
        $queryCreator = new EmailsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `emails` (`email`) VALUES ('" . self::$_email . "')";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
