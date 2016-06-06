<?php

namespace app\queries;

use app\mappers\UsersRulesInsertMapper;
use app\queries\UsersRulesInsertQueryCreator;
use app\models\UsersRulesModel;

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
        $usersRulesInsertMapper = new UsersRulesInsertMapper([
            'tableName'=>'users_rules',
            'fields'=>['id_users', 'id_rules'],
            'objectsArray'=>[new UsersRulesModel(['id_users'=>1, 'id_rules'=>2]), new UsersRulesModel(['id_users'=>2, 'id_rules'=>2])]
        ]);
        
        $usersRulesInsertMapper->visit(new UsersRulesInsertQueryCreator());
        
        $query = 'INSERT INTO {{users_rules}} (id_users,id_rules) VALUES (:0_id_users,:0_id_rules),(:1_id_users,:1_id_rules)';
        
        $this->assertEquals($query, $usersRulesInsertMapper->query);
    }
}
