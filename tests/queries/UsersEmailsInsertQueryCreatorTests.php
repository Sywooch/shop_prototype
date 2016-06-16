<?php

namespace app\queries;

use app\mappers\UsersEmailsInsertMapper;
use app\queries\UsersEmailsInsertQueryCreator;
use app\models\UsersEmailsModel;

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
        $usersEmailsInsertMapper = new UsersEmailsInsertMapper([
            'tableName'=>'users_emails',
            'fields'=>['id_users', 'id_emails'],
            'objectsArray'=>[new UsersEmailsModel(['id_users'=>1, 'id_emails'=>2]), new UsersEmailsModel(['id_users'=>2, 'id_emails'=>2])]
        ]);
        
        $usersEmailsInsertMapper->visit(new UsersEmailsInsertQueryCreator());
        
        $query = 'INSERT INTO {{users_emails}} (id_users,id_emails) VALUES (:0_id_users,:0_id_emails),(:1_id_users,:1_id_emails)';
        
        $this->assertEquals($query, $usersEmailsInsertMapper->query);
    }
}
