<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\MailingListByIdQueryCreator;

/**
 * Тестирует класс app\queries\MailingListByIdQueryCreator
 */
class MailingListByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 33;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'mailing_list',
            'fields'=>['id', 'name', 'description'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new MailingListByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `mailing_list`.`id`, `mailing_list`.`name`, `mailing_list`.`description` FROM `mailing_list` WHERE `mailing_list`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
