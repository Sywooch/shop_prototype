<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\UsersInsertQueryCreator;

/**
 * Тестирует класс app\queries\UsersInsertQueryCreator
 */
class UsersInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[34, 'hj89Hfk', 'Name', 'Surname', 78, 11]];
    
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
            'tableName'=>'users',
            'fields'=>['id_emails', 'password', 'name', 'surname', 'id_phones', 'id_address'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new UsersInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `users` (`id_emails`, `password`, `name`, `surname`, `id_phones`, `id_address`) VALUES (" . self::$_params[0][0] . ", '" . self::$_params[0][1] . "', '" . self::$_params[0][2] . "', '" . self::$_params[0][3] . "', " . self::$_params[0][4] . ", " . self::$_params[0][5] . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
