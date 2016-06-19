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
    private static $_dbClass;
    private static $_id = 1;
    private static $_login = 'Somelogin';
    private static $_password = 'Somepassword';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_email = 'some@some.com';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[password]]=:password, [[name]]=:name, [[surname]]=:surname');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':password'=>self::$_password, ':name'=>self::$_name, ':surname'=>self::$_surname]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users_emails}} SET [[id_users]]=:id_users, [[id_emails]]=:id_emails');
        $command->bindValues([':id_users'=>self::$_id, ':id_emails'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует метод UsersEmailsByUsersEmailsMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $usersEmailsByUsersEmailsMapper = new UsersEmailsByUsersEmailsMapper([
            'tableName'=>'users_emails',
            'fields'=>['id_users', 'id_emails'],
            'params'=>[
                ':id_users'=>self::$_id, 
                ':id_emails'=>self::$_id,
            ]
        ]);
        $usersEmailsByUsersEmailsModel = $usersEmailsByUsersEmailsMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($usersEmailsByUsersEmailsModel));
        $this->assertTrue($usersEmailsByUsersEmailsModel instanceof UsersEmailsModel);
        
        $this->assertTrue(property_exists($usersEmailsByUsersEmailsModel, 'id_users'));
        $this->assertTrue(property_exists($usersEmailsByUsersEmailsModel, 'id_emails'));
        
        $this->assertTrue(isset($usersEmailsByUsersEmailsModel->id_users));
        $this->assertTrue(isset($usersEmailsByUsersEmailsModel->id_emails));
        
        $this->assertEquals(self::$_id, $usersEmailsByUsersEmailsModel->id_users);
        $this->assertEquals(self::$_id, $usersEmailsByUsersEmailsModel->id_emails);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
