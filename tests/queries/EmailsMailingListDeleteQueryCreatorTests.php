<?php

namespace app\tests\queries;

use app\tests\{MockObject, 
    MockModel};
use app\queries\EmailsMailingListDeleteQueryCreator;

/**
 * Тестирует класс app\queries\EmailsMailingListDeleteQueryCreator
 */
class EmailsMailingListDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'emails_mailing_list',
            'fields'=>['id_email', 'id_mailing_list'],
            'objectsArray'=>[
                new MockModel(['id_email'=>self::$_id, 'id_mailing_list'=>self::$_id]),
                new MockModel(['id_email'=>self::$_id, 'id_mailing_list'=>self::$_id]),
            ],
        ]);
        
        $queryCreator = new EmailsMailingListDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'DELETE FROM {{emails_mailing_list}} WHERE [[emails_mailing_list.id_email]] IN (:0_id_email,:1_id_email) AND [[emails_mailing_list.id_mailing_list]] IN (:0_id_mailing_list,:1_id_mailing_list)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
