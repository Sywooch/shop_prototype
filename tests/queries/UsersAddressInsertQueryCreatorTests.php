<?php

namespace app\queries;

use app\tests\MockObject;
use app\tests\MockModel;
use app\queries\UsersAddressInsertQueryCreator;

/**
 * Тестирует класс app\queries\UsersAddressInsertQueryCreator
 */
class UsersAddressInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users_address',
            'fields'=>['id_users', 'id_address'],
            'objectsArray'=>[
                new MockModel(['id_users'=>1, 'id_address'=>2]),
                new MockModel(['id_users'=>2, 'id_address'=>2]),
                new MockModel(['id_users'=>3, 'id_address'=>3]),
            ],
        ]);
        
        $queryCreator = new UsersAddressInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{users_address}} (id_users,id_address) VALUES (:0_id_users,:0_id_address),(:1_id_users,:1_id_address),(:2_id_users,:2_id_address)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
