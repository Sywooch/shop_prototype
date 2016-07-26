<?php

namespace app\queries;

use app\tests\MockObject;
use app\queries\UsersByIdQueryCreator;

/**
 * Тестирует класс app\queries\UsersByIdQueryCreator
 */
class UsersByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users',
            'fields'=>['id', 'login', 'password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
        ]);
        
        $queryCreator = new UsersByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[users.id]],[[users.login]],[[users.password]],[[users.name]],[[users.surname]],[[users.id_emails]],[[users.id_phones]],[[users.id_address]] FROM {{users}} WHERE [[users.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
