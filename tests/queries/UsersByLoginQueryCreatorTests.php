<?php

namespace app\queries;

use app\tests\MockObject;
use app\queries\UsersByLoginQueryCreator;

/**
 * Тестирует класс app\queries\UsersByLoginQueryCreator
 */
class UsersByLoginQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new UsersByLoginQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[users.id]],[[users.login]],[[users.password]],[[users.name]],[[users.surname]],[[users.id_emails]],[[users.id_phones]],[[users.id_address]] FROM {{users}} WHERE [[users.login]]=:login';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
