<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\CommentsInsertQueryCreator;

/**
 * Тестирует класс app\queries\CommentsInsertQueryCreator
 */
class CommentsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [['Some text', 'John', 'some@some.com', 98]];
    
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
            'fields'=>['text', 'name', 'email', 'id_products'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new CommentsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `comments` (`text`, `name`, `email`, `id_products`) VALUES ('" . self::$_params[0][0] . "', '" . self::$_params[0][1] . "', '" . self::$_params[0][2] . "', " . self::$_params[0][3] . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
