<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\UsersPhonesByUsersPhonesQueryCreator;

/**
 * Тестирует класс app\queries\UsersPhonesByUsersPhonesQueryCreator
 */
class UsersPhonesByUsersPhonesQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users_phones',
            'fields'=>['id_users', 'id_phones'],
        ]);
        
        $queryCreator = new UsersPhonesByUsersPhonesQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[users_phones.id_users]],[[users_phones.id_phones]] FROM {{users_phones}} WHERE [[users_phones.id_users]]=:id_users AND [[users_phones.id_phones]]=:id_phones';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
