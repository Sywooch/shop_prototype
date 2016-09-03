<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\EmailsMailingListInsertQueryCreator;

/**
 * Тестирует класс app\queries\EmailsMailingListInsertQueryCreator
 */
class EmailsMailingListInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[2, 90]];
    
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
            'tableName'=>'emails_mailing_list',
            'fields'=>['id_email', 'id_mailing_list'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new EmailsMailingListInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `emails_mailing_list` (`id_email`, `id_mailing_list`) VALUES (" . implode(', ', self::$_params[0]) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
