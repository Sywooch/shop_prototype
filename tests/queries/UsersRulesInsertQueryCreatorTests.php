<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\UsersRulesInsertQueryCreator;

/**
 * Тестирует класс app\queries\UsersRulesInsertQueryCreator
 */
class UsersRulesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[2, 89], [13, 66]];
    
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
            'tableName'=>'users_rules',
            'fields'=>['id_users', 'id_rules'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new UsersRulesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `users_rules` (`id_users`, `id_rules`) VALUES (" . implode(', ', self::$_params[0]) . "), (" . implode(', ', self::$_params[1]) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
