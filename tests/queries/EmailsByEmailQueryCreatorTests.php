<?php

namespace app\tests\queries;

use app\tests\{DbManager, 
    MockModel,
    MockObject};
use app\queries\EmailsByEmailQueryCreator;

/**
 * Тестирует класс app\queries\EmailsByEmailQueryCreator
 */
class EmailsByEmailQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'emails',
            'fields'=>['id', 'email'],
            'model'=>new MockModel(['email'=>self::$_email])
        ]);
        
        $queryCreator = new EmailsByEmailQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `emails`.`id`, `emails`.`email` FROM `emails` WHERE `emails`.`email`='" . self::$_email . "'";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
