<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\UsersRulesInsertMapper;

/**
 * Тестирует класс app\mappers\UsersRulesInsertMapper
 */
class UsersRulesInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_login = 'Somelogin';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_rule = 'Some Rule';
    private static $_rule2 = 'Some Rule Two';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[name]]=:name, [[surname]]=:surname');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':name'=>self::$_name, ':surname'=>self::$_surname]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{rules}} SET [[id]]=:id, [[rule]]=:rule');
        $command->bindValues([':id'=>self::$_id, ':rule'=>self::$_rule]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{rules}} SET [[id]]=:id, [[rule]]=:rule');
        $command->bindValues([':id'=>self::$_id + 1, ':rule'=>self::$_rule2]);
        $command->execute();
    }
    
    /**
     * Тестирует метод UsersRulesInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $usersRulesInsertMapper = new UsersRulesInsertMapper([
            'tableName'=>'users_rules',
            'fields'=>['id_users', 'id_rules'],
            'model'=>new MockModel([
                'id'=>self::$_id,
                'rulesFromForm'=>[self::$_id, self::$_id + 1],
            ]),
        ]);
        $result = $usersRulesInsertMapper->setGroup();
        
        $this->assertEquals(2, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{users_rules}} WHERE id_users=:id_users');
        $command->bindValue(':id_users', self::$_id);
        $result = $command->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(2, count($result));
        
        $this->assertTrue(is_array($result[0]));
        $this->assertFalse(empty($result[0]));
        
        $this->assertArrayHasKey('id_users', $result[0]);
        $this->assertArrayHasKey('id_rules', $result[0]);
        
        $this->assertEquals(self::$_id, $result[0]['id_users']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
