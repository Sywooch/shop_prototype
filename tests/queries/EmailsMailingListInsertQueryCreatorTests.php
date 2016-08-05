<?php

namespace app\queries;

use app\tests\MockObject;
use app\tests\MockModel;
use app\queries\EmailsMailingListInsertQueryCreator;

/**
 * Тестирует класс app\queries\EmailsMailingListInsertQueryCreator
 */
class EmailsMailingListInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    
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
        
        $query = 'INSERT INTO {{emails_mailing_list}} (id_email,id_mailing_list) VALUES (:0_id_email,:0_id_mailing_list)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
