<?php

namespace app\queries;

use app\tests\MockObject;
use app\queries\UsersByIdEmailsQueryCreator;

/**
 * Тестирует класс app\queries\UsersByIdEmailsQueryCreator
 */
class UsersByIdEmailsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users',
            'fields'=>['id', 'id_emails', 'password', 'name', 'surname', 'id_phones', 'id_address'],
        ]);
        
        $queryCreator = new UsersByIdEmailsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[users.id]],[[users.id_emails]],[[users.password]],[[users.name]],[[users.surname]],[[users.id_phones]],[[users.id_address]] FROM {{users}} WHERE [[users.id_emails]]=:id_emails';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
