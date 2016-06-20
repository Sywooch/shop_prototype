<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\UsersEmailsInsertMapper;

/**
 * Тестирует класс app\mappers\UsersEmailsInsertMapper
 */
class UsersEmailsInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_email = 'some@some.com';
    private static $_email2 = 'some2@some.com';
    private static $_login = 'Somelogin';
    private static $_login2 = 'Somelogin2';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id + 1, ':email'=>self::$_email2]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[name]]=:name, [[surname]]=:surname');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':name'=>self::$_name, ':surname'=>self::$_surname]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[name]]=:name, [[surname]]=:surname');
        $command->bindValues([':id'=>self::$_id + 1, ':login'=>self::$_login2, ':name'=>self::$_name, ':surname'=>self::$_surname]);
        $command->execute();
        
    }
    
    /**
     * Тестирует метод UsersEmailsInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $usersEmailsInsertMapper = new UsersEmailsInsertMapper([
            'tableName'=>'users_emails',
            'fields'=>['id_users', 'id_emails'],
            'DbArray'=>[
                ['id_users'=>self::$_id, 'id_emails'=>self::$_id],
                ['id_users'=>self::$_id + 1, 'id_emails'=>self::$_id + 1],
            ],
        ]);
        $result = $usersEmailsInsertMapper->setGroup();
        
        $this->assertEquals(2, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{users_emails}}');
        $result = $command->queryAll();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals(2, count($result));
        
        $this->assertArrayHasKey('id_users', $result[0]);
        $this->assertArrayHasKey('id_emails', $result[0]);
        
        $this->assertTrue(isset($result[0]['id_users']));
        $this->assertTrue(isset($result[0]['id_emails']));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
