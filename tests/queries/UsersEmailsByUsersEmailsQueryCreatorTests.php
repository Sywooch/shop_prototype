<?php

namespace app\tests\queries;

use app\queries\UsersEmailsByUsersEmailsQueryCreator;
use app\mappers\UsersEmailsByUsersEmailsMapper;

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
        $usersEmailsByUsersEmailsMapper = new UsersEmailsByUsersEmailsMapper([
            'tableName'=>'users_emails',
            'fields'=>['id_users', 'id_emails'],
            'params'=>[':id_users'=>1, ':id_emails'=>2]
        ]);
        $usersEmailsByUsersEmailsMapper->visit(new UsersEmailsByUsersEmailsQueryCreator());
        
        $query = 'SELECT [[users_emails.id_users]],[[users_emails.id_emails]] FROM {{users_emails}} WHERE [[users_emails.id_users]]=:id_users AND [[users_emails.id_emails]]=:id_emails';
        
        $this->assertEquals($query, $usersEmailsByUsersEmailsMapper->query);
    }
}
