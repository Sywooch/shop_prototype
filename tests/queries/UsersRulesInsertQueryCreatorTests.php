<?php

namespace app\queries;

use app\tests\MockObject;
use app\tests\MockModel;
use app\queries\UsersRulesInsertQueryCreator;

/**
 * Тестирует класс app\queries\UsersRulesInsertQueryCreator
 */
class UsersRulesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users_rules',
            'fields'=>['id_users', 'id_rules'],
            'objectsArray'=>[
                new MockModel(['id_users'=>1, 'id_rules'=>2]),
                new MockModel(['id_users'=>2, 'id_rules'=>2]),
                new MockModel(['id_users'=>3, 'id_rules'=>4]),
            ]
        ]);
        
        $queryCreator = new UsersRulesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{users_rules}} (id_users,id_rules) VALUES (:0_id_users,:0_id_rules),(:1_id_users,:1_id_rules),(:2_id_users,:2_id_rules)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
