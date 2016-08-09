<?php

namespace app\tests\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\UsersUpdateQueryCreator;

/**
 * Тестирует класс app\queries\UsersUpdateQueryCreator
 */
class UsersUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_some = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users',
            'fields'=>['id', 'id_emails', 'name', 'surname', 'id_phones', 'id_address'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_some, 
                    'id_emails'=>self::$_some, 
                    'name'=>self::$_some, 
                    'surname'=>self::$_some, 
                    'id_phones'=>self::$_some, 
                    'id_address'=>self::$_some, 
                ]),
            ],
        ]);
        
        $queryCreator = new UsersUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{users}} (id,id_emails,name,surname,id_phones,id_address) VALUES (:0_id,:0_id_emails,:0_name,:0_surname,:0_id_phones,:0_id_address) ON DUPLICATE KEY UPDATE id=VALUES(id),id_emails=VALUES(id_emails),name=VALUES(name),surname=VALUES(surname),id_phones=VALUES(id_phones),id_address=VALUES(id_address)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
