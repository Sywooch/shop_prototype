<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject,
    MockModel};
use app\queries\EmailsMailingListInsertQueryCreator;

/**
 * Тестирует класс app\queries\EmailsMailingListInsertQueryCreator
 */
class EmailsMailingListInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
            'tableName'=>'emails_mailing_list',
            'fields'=>['id_email', 'id_mailing_list'],
            'objectsArray'=>[
                new MockModel([
                    'id_email'=>self::$_id,
                    'id_mailing_list'=>self::$_id,
                ]),
            ],
        ]);
        
        $queryCreator = new EmailsMailingListInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `emails_mailing_list` (`id_email`, `id_mailing_list`) VALUES (" . self::$_id . ', ' . self::$_id . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
