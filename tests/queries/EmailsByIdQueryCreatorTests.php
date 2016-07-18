<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\EmailsByIdQueryCreator;

/**
 * Тестирует класс app\queries\EmailsByIdQueryCreator
 */
class EmailsByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new EmailsByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[emails.id]],[[emails.email]] FROM {{emails}} WHERE [[emails.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
