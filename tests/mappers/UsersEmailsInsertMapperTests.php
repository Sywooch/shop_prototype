<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\UsersEmailsInsertMapper;
use app\models\UsersEmailsModel;

/**
 * Тестирует класс app\mappers\UsersEmailsInsertMapper
 */
class UsersEmailsInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод UsersEmailsInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $config = ['id_users'=>234, 'id_emails'=>23];
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id');
        $command->bindValue(':id', $config['id_users']);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id');
        $command->bindValue(':id', $config['id_emails']);
        $command->execute();
        
        $usersEmailsInsertMapper = new UsersEmailsInsertMapper([
            'tableName'=>'users_emails',
            'fields'=>['id_users', 'id_emails'],
            'DbArray'=>[$config],
        ]);
        $result = $usersEmailsInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{users_emails}} WHERE id_users=:id_users');
        $command->bindValue(':id_users', $config['id_users']);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id_users', $result);
        $this->assertArrayHasKey('id_emails', $result);
        
        $this->assertEquals($config['id_users'], $result['id_users']);
        $this->assertEquals($config['id_emails'], $result['id_emails']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
