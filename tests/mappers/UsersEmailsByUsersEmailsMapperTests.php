<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\UsersEmailsByUsersEmailsMapper;
use app\models\UsersEmailsModel;

/**
 * Тестирует класс app\mappers\UsersEmailsByUsersEmailsMapper
 */
class UsersEmailsByUsersEmailsMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод UsersEmailsByUsersEmailsMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $config = ['id_users'=>178, 'id_emails'=>56];
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id');
        $command->bindValues([':id'=>$config['id_users']]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>$config['id_emails'], ':email'=>'some@some.com']);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users_emails}} SET [[id_users]]=:id_users, [[id_emails]]=:id_emails');
        $command->bindValues([':id_users'=>$config['id_users'], ':id_emails'=>$config['id_emails']]);
        $command->execute();
        
        $usersEmailsByUsersEmailsMapper = new UsersEmailsByUsersEmailsMapper([
            'tableName'=>'users_emails',
            'fields'=>['id_users', 'id_emails'],
            'params'=>[':id_users'=>$config['id_users'], ':id_emails'=>$config['id_emails']]
        ]);
        $usersEmailsByUsersEmailsModel = $usersEmailsByUsersEmailsMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($usersEmailsByUsersEmailsModel));
        $this->assertTrue($usersEmailsByUsersEmailsModel instanceof UsersEmailsModel);
        
        $this->assertTrue(property_exists($usersEmailsByUsersEmailsModel, 'id_users'));
        $this->assertTrue(property_exists($usersEmailsByUsersEmailsModel, 'id_emails'));
        
        $this->assertTrue(isset($usersEmailsByUsersEmailsModel->id_users));
        $this->assertTrue(isset($usersEmailsByUsersEmailsModel->id_emails));
        
        $this->assertEquals($config['id_users'], $usersEmailsByUsersEmailsModel->id_users);
        $this->assertEquals($config['id_emails'], $usersEmailsByUsersEmailsModel->id_emails);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
