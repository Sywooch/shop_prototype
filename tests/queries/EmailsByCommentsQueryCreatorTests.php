<?php

namespace app\tests\queries;

use app\queries\EmailsByCommentsQueryCreator;
use app\mappers\EmailsByCommentsMapper;

/**
 * Тестирует класс app\queries\EmailsByCommentsQueryCreator
 */
class EmailsByCommentsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $emailsByCommentsMapper = new EmailsByCommentsMapper([
            'tableName'=>'emails',
            'fields'=>['id'],
        ]);
        $emailsByCommentsMapper->visit(new EmailsByCommentsQueryCreator());
        
        $query = 'SELECT [[emails.id]] FROM {{emails}} WHERE [[emails.email]]=:email';
        
        $this->assertEquals($query, $emailsByCommentsMapper->query);
    }
}
