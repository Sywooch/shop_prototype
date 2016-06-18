<?php

namespace app\queries;

use app\tests\MockObject;
use app\tests\MockModel;
use app\queries\UsersInsertQueryCreator;

/**
 * Тестирует класс app\queries\UsersInsertQueryCreator
 */
class UsersInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users',
            'fields'=>['login', 'password', 'name', 'surname'],
            'objectsArray'=>[new MockModel(['login'=>'user', 'password'=>'password', 'name'=>'name'])],
        ]);
        
        $queryCreator = new UsersInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{users}} (login,password,name,surname) VALUES (:0_login,:0_password,:0_name,:0_surname)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
