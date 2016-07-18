<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\UsersUpdateQueryCreator;

/**
 * Тестирует класс app\queries\UsersUpdateQueryCreator
 */
class UsersUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users',
            'fields'=>['name', 'surname', 'id_emails', 'id_phones', 'id_address'],
        ]);
        
        $queryCreator = new UsersUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'UPDATE {{users}} SET [[name]]=:name,[[surname]]=:surname,[[id_emails]]=:id_emails,[[id_phones]]=:id_phones,[[id_address]]=:id_address WHERE [[users.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
