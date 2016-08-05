<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\MailingListForEmailQueryCreator;

/**
 * Тестирует класс app\queries\MailingListForEmailQueryCreator
 */
class MailingListForEmailQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'mailing_list',
            'fields'=>['id', 'name', 'description'],
        ]);
        
        $queryCreator = new MailingListForEmailQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[mailing_list.id]],[[mailing_list.name]],[[mailing_list.description]] FROM {{mailing_list}} JOIN {{emails_mailing_list}} ON [[mailing_list.id]]=[[emails_mailing_list.id_mailing_list]] JOIN {{emails}} ON [[emails_mailing_list.id_email]]=[[emails.id]] WHERE [[emails.email]]=:email';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
