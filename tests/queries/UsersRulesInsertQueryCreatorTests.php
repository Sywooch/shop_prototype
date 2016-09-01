<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject,
    MockModel};
use app\queries\UsersRulesInsertQueryCreator;

/**
 * Тестирует класс app\queries\UsersRulesInsertQueryCreator
 */
class UsersRulesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 2;
    
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
            'objectsArray'=>[
                new MockModel(['id_users'=>self::$_id, 'id_rules'=>self::$_id + 2]),
                new MockModel(['id_users'=>self::$_id + 2, 'id_rules'=>self::$_id * 2]),
                new MockModel(['id_users'=>self::$_id * 3, 'id_rules'=>self::$_id + 4]),
            ]
        ]);
        
        $queryCreator = new UsersRulesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `users_rules` (`id_users`, `id_rules`) VALUES (" . self::$_id . ', ' . (self::$_id+2) . "), (" . (self::$_id+2) . ', ' . (self::$_id*2) . "), (" . (self::$_id*3) . ', ' . (self::$_id+4) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
