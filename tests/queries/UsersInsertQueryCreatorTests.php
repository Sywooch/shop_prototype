<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\UsersInsertQueryCreator;

/**
 * Тестирует класс app\queries\UsersInsertQueryCreator
 */
class UsersInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id_emails = 32;
    private static $_password = 'ghJh4k';
    private static $_name = 'Name';
    private static $_surname = 'Surname';
    private static $_id_phones = 12;
    private static $_id_address = 2;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users',
            'fields'=>['id_emails', 'password', 'name', 'surname', 'id_phones', 'id_address'],
            'objectsArray'=>[
                new MockModel([
                    'id_emails'=>self::$_id_emails, 
                    'password'=>self::$_password, 
                    'name'=>self::$_name, 
                    'surname'=>self::$_surname, 
                    'id_phones'=>self::$_id_phones, 
                    'id_address'=>self::$_id_address, 
                ]),
            ],
        ]);
        
        $queryCreator = new UsersInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{users}} (id_emails,password,name,surname,id_phones,id_address) VALUES (:0_id_emails,:0_password,:0_name,:0_surname,:0_id_phones,:0_id_address)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
