<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\EmailsByEmailQueryCreator;

/**
 * Тестирует класс app\queries\EmailsByEmailQueryCreator
 */
class EmailsByEmailQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'emails',
            'fields'=>['id', 'email'],
        ]);
        
        $queryCreator = new EmailsByEmailQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[emails.id]],[[emails.email]] FROM {{emails}} WHERE [[emails.email]]=:email';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
