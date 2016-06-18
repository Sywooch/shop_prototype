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
            'fields'=>['id', 'login', 'name', 'surname'],
        ]);
        
        $queryCreator = new UsersByLoginQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[users.id]],[[users.login]],[[users.name]],[[users.surname]] FROM {{users}} WHERE [[users.login]]=:login';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
