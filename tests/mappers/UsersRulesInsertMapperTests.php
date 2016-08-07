<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\UsersRulesInsertMapper;
use app\models\UsersModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\UsersRulesInsertMapper
 */
class UsersRulesInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_id_emails = 21;
    private static $_login = 'Somelogin';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_rule = 'Some Rule';
    private static $_rule2 = 'Some Rule Two';
    private static $_email = 'some@some.com';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id_emails, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id_emails, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{rules}} SET [[id]]=:id, [[rule]]=:rule');
        $command->bindValues([':id'=>self::$_id, ':rule'=>self::$_rule]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{rules}} SET [[id]]=:id, [[rule]]=:rule');
        $command->bindValues([':id'=>self::$_id + 3, ':rule'=>self::$_rule2]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод UsersRulesInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $usersRulesInsertMapper = new UsersRulesInsertMapper([
            'tableName'=>'users_rules',
            'fields'=>['id_users', 'id_rules'],
            'model'=>new UsersModel([
                'id'=>self::$_id,
                'rulesFromForm'=>[self::$_id, self::$_id + 3],
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
    
    /**
     * Тестирует метод UsersRulesInsertMapper::setGroup
     * при назначении rules по умолчанию
     */
    public function testSetGroupDefault()
    {
        \Yii::$app->db->createCommand('DELETE FROM {{users_rules}}')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{users_rules}}')->queryAll()));
        
        $usersRulesInsertMapper = new UsersRulesInsertMapper([
            'tableName'=>'users_rules',
            'fields'=>['id_users', 'id_rules'],
            'model'=>new UsersModel([
                'id'=>self::$_id,
            ]),
        ]);
        $result = $usersRulesInsertMapper->setGroup();
        
        $this->assertEquals(2, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
