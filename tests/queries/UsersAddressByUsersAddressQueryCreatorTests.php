<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\UsersAddressByUsersAddressQueryCreator;

/**
 * Тестирует класс app\queries\UsersAddressByUsersAddressQueryCreator
 */
class UsersAddressByUsersAddressQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users_address',
            'fields'=>['id_users', 'id_address'],
        ]);
        
        $queryCreator = new UsersAddressByUsersAddressQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[users_address.id_users]],[[users_address.id_address]] FROM {{users_address}} WHERE [[users_address.id_users]]=:id_users AND [[users_address.id_address]]=:id_address';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
