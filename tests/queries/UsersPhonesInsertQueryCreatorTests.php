<?php

namespace app\queries;

use app\tests\MockObject;
use app\tests\MockModel;
use app\queries\UsersPhonesInsertQueryCreator;

/**
 * Тестирует класс app\queries\UsersPhonesInsertQueryCreator
 */
class UsersPhonesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users_phones',
            'fields'=>['id_users', 'id_phones'],
            'objectsArray'=>[
                new MockModel(['id_users'=>1, 'id_phones'=>2]),
                new MockModel(['id_users'=>2, 'id_phones'=>2]),
            ],
        ]);
        
        $queryCreator = new UsersPhonesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{users_phones}} (id_users,id_phones) VALUES (:0_id_users,:0_id_phones),(:1_id_users,:1_id_phones)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
