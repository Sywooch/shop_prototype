<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\CommentsUpdateQueryCreator;

/**
 * Тестирует класс app\queries\CommentsUpdateQueryCreator
 */
class CommentsUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[23, 'Some text', 'John', 5, 11, 1]];
    
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
            'tableName'=>'comments',
            'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new CommentsUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `comments` (`id`, `text`, `name`, `id_emails`, `id_products`, `active`) VALUES (" . self::$_params[0][0] . ", '" . self::$_params[0][1] . "', '" . self::$_params[0][2] . "', " . self::$_params[0][3] . ', ' . self::$_params[0][4] . ', ' . self::$_params[0][5] . ") ON DUPLICATE KEY UPDATE `text`=VALUES(`text`), `name`=VALUES(`name`), `id_emails`=VALUES(`id_emails`), `id_products`=VALUES(`id_products`), `active`=VALUES(`active`)";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
