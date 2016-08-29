<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockModel,
    MockObject};
use app\queries\MailingListForEmailQueryCreator;

/**
 * Тестирует класс app\queries\MailingListForEmailQueryCreator
 */
class MailingListForEmailQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
            'tableName'=>'mailing_list',
            'fields'=>['id', 'name', 'description'],
            'model'=>new MockModel(['email'=>self::$_email])
        ]);
        
        $queryCreator = new MailingListForEmailQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `mailing_list`.`id`, `mailing_list`.`name`, `mailing_list`.`description` FROM `mailing_list` INNER JOIN `emails_mailing_list` ON `mailing_list`.`id`=`emails_mailing_list`.`id_mailing_list` INNER JOIN `emails` ON `emails_mailing_list`.`id_email`=`emails`.`id` WHERE `emails`.`email`='" . self::$_email . "'";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
