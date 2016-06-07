<?php

namespace app\queries;

use app\mappers\UsersByLoginMapper;
use app\queries\UsersByLoginQueryCreator;
use app\models\UsersModel;

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
        $usersByLoginMapper = new UsersByLoginMapper([
            'tableName'=>'users',
            'fields'=>['id', 'login', 'name'],
            'model'=>new UsersModel(['login'=>'user']),
        ]);
        
        $usersByLoginMapper->visit(new UsersByLoginQueryCreator());
        
        $query = 'SELECT [[users.id]],[[users.login]],[[users.name]] FROM {{users}} WHERE [[users.login]]=:login';
        
        $this->assertEquals($query, $usersByLoginMapper->query);
    }
}
