<?php

namespace app\tests\factories;

use app\tests\DbManager;
use app\factories\UsersEmailsOneObjectFactory;
use app\mappers\UsersEmailsByUsersEmailsMapper;
use app\queries\UsersEmailsByUsersEmailsQueryCreator;
use app\models\UsersEmailsModel;

/**
 * Тестирует класс app\factories\UsersEmailsOneObjectFactory
 */
class UsersEmailsOneObjectFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод UsersEmailsOneObjectFactory::getOne()
     */
    public function testGetOne()
    {
        $config = ['id_users'=>156, 'id_emails'=>287];
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id');
        $command->bindValue(':id', $config['id_users']);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id');
        $command->bindValue(':id', $config['id_emails']);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users_emails}} SET [[id_users]]=:id_users, [[id_emails]]=:id_emails');
        $command->bindValues([':id_users'=>$config['id_users'], ':id_emails'=>$config['id_emails']]);
        $command->execute();
        
        $usersEmailsByUsersEmailsMapper = new UsersEmailsByUsersEmailsMapper([
            'tableName'=>'users_emails',
            'fields'=>['id_users', 'id_emails'],
            'params'=>[':id_users'=>$config['id_users'], ':id_emails'=>$config['id_emails']]
        ]);
        
        $this->assertFalse(isset($usersEmailsByUsersEmailsMapper->objectsOne));
        $this->assertEmpty($usersEmailsByUsersEmailsMapper->DbArray);
        
        $usersEmailsByUsersEmailsMapper->visit(new UsersEmailsByUsersEmailsQueryCreator());
        
        $command = \Yii::$app->db->createCommand($usersEmailsByUsersEmailsMapper->query);
        $command->bindValues($config);
        $usersEmailsByUsersEmailsMapper->DbArray = $command->queryOne();
        
        $this->assertFalse(empty($usersEmailsByUsersEmailsMapper->DbArray));
        
        $usersEmailsByUsersEmailsMapper->visit(new UsersEmailsOneObjectFactory());
        
        $this->assertTrue(isset($usersEmailsByUsersEmailsMapper->objectsOne));
        $this->assertTrue(is_object($usersEmailsByUsersEmailsMapper->objectsOne));
        $this->assertTrue($usersEmailsByUsersEmailsMapper->objectsOne instanceof UsersEmailsModel);
        
        $this->assertTrue(property_exists($usersEmailsByUsersEmailsMapper->objectsOne, 'id_users'));
        $this->assertTrue(property_exists($usersEmailsByUsersEmailsMapper->objectsOne, 'id_emails'));
        
        $this->assertTrue(isset($usersEmailsByUsersEmailsMapper->objectsOne->id_users));
        $this->assertTrue(isset($usersEmailsByUsersEmailsMapper->objectsOne->id_emails));
        
        $this->assertEquals($config['id_users'], $usersEmailsByUsersEmailsMapper->objectsOne->id_users);
        $this->assertEquals($config['id_emails'], $usersEmailsByUsersEmailsMapper->objectsOne->id_emails);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
