<?php

namespace app\queries;

use app\tests\MockObject;
use app\tests\MockModel;
use app\queries\UsersEmailsInsertQueryCreator;

/**
 * Тестирует класс app\queries\UsersEmailsInsertQueryCreator
 */
class UsersEmailsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users_emails',
            'fields'=>['id_users', 'id_emails'],
            'objectsArray'=>[
                new MockModel(['id_users'=>1, 'id_emails'=>2]),
                new MockModel(['id_users'=>2, 'id_emails'=>2]),
                new MockModel(['id_users'=>3, 'id_emails'=>3]),
            ],
        ]);
        
        $queryCreator = new UsersEmailsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{users_emails}} (id_users,id_emails) VALUES (:0_id_users,:0_id_emails),(:1_id_users,:1_id_emails),(:2_id_users,:2_id_emails)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
