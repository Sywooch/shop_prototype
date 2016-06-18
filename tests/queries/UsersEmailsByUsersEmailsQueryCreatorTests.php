<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\UsersEmailsByUsersEmailsQueryCreator;

/**
 * Тестирует класс app\queries\UsersEmailsByUsersEmailsQueryCreator
 */
class UsersEmailsByUsersEmailsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users_emails',
            'fields'=>['id_users', 'id_emails'],
        ]);
        
        $queryCreator = new UsersEmailsByUsersEmailsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[users_emails.id_users]],[[users_emails.id_emails]] FROM {{users_emails}} WHERE [[users_emails.id_users]]=:id_users AND [[users_emails.id_emails]]=:id_emails';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
