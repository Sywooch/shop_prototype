<?php

namespace app\tests\mappers;

use app\mappers\UsersRulesInsertMapper;
use app\tests\DbManager;
use app\models\UsersRulesModel;

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
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} ([[login]],[[password]]) VALUES (:login1,:pass1), (:login2,:pass2)');
        $command->bindValues([':login1'=>'login1', ':pass1'=>'pass1', ':login2'=>'login2', ':pass2'=>'pass2']);
        $command->execute();
        
        $entry1 = ['id_users'=>1, 'id_rules'=>1];
        $entry2 = ['id_users'=>2, 'id_rules'=>2];
        
        $usersRulesInsertMapper = new UsersRulesInsertMapper([
            'tableName'=>'users_rules',
            'fields'=>['id_users', 'id_rules'],
            'objectsArray'=>[new UsersRulesModel($entry1), new UsersRulesModel($entry2)]
        ]);
        $result = $usersRulesInsertMapper->setGroup();
        
        $this->assertEquals(2, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{users_rules}} WHERE id_users=:id_users');
        $command->bindValue(':id_users', $entry2['id_users']);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id_users', $result);
        $this->assertArrayHasKey('id_rules', $result);
        
        $this->assertEquals($entry2['id_users'], $result['id_users']);
        $this->assertEquals($entry2['id_rules'], $result['id_rules']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
