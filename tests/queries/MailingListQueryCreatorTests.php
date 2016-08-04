<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\MailingListQueryCreator;

/**
 * Тестирует класс app\queries\MailingListQueryCreator
 */
class MailingListQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new MailingListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[mailing_list.id]],[[mailing_list.name]],[[mailing_list.description]] FROM {{mailing_list}}';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
