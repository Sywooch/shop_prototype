<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\UsersUpdateQueryCreator;

/**
 * Тестирует класс app\queries\UsersUpdateQueryCreator
 */
class UsersUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[3, 6, 'John', 'Doe', 3, 12]];
    
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
            'tableName'=>'users',
            'fields'=>['id', 'id_emails', 'name', 'surname', 'id_phones', 'id_address'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new UsersUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `users` (`id`, `id_emails`, `name`, `surname`, `id_phones`, `id_address`) VALUES (" . self::$_params[0][0] . ', ' . self::$_params[0][1] . ", '" . self::$_params[0][2] . "', '" . self::$_params[0][3] . "', " . self::$_params[0][4] . ', ' . self::$_params[0][5] . ") ON DUPLICATE KEY UPDATE `id_emails`=VALUES(`id_emails`), `name`=VALUES(`name`), `surname`=VALUES(`surname`), `id_phones`=VALUES(`id_phones`), `id_address`=VALUES(`id_address`)";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
