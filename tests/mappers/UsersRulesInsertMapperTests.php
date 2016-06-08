<?php

namespace app\tests\mappers;

use app\mappers\UsersRulesInsertMapper;
use app\tests\DbManager;
use app\models\UsersRulesModel;
use app\models\UsersModel;

/**
 * Тестирует класс app\mappers\UsersRulesInsertMapper
 */
class UsersRulesInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод UsersRulesInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} ([[login]],[[password]]) VALUES (:login1,:pass1)');
        $command->bindValues([':login1'=>'login1', ':pass1'=>'pass1']);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{rules}} ([[rule]]) VALUES (:rule1), (:rule2)');
        $command->bindValues([':rule1'=>'add data', ':rule2'=>'add user']);
        $command->execute();
        
        $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
        $usersModel->attributes = ['login'=>'login1', 'rulesFromForm'=>[1,2]];
        
        $usersRulesInsertMapper = new UsersRulesInsertMapper([
            'tableName'=>'users_rules',
            'fields'=>['id_users', 'id_rules'],
            'model'=>$usersModel
        ]);
        $result = $usersRulesInsertMapper->setGroup();
        
        $this->assertEquals(2, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{users_rules}} WHERE id_users=:id_users');
        $command->bindValue(':id_users', $usersModel->id);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id_users', $result);
        $this->assertArrayHasKey('id_rules', $result);
        
        $this->assertEquals($usersModel->id, $result['id_users']);
        //$this->assertEquals($entry2['id_rules'], $result['id_rules']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
