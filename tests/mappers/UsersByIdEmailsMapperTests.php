<?php

namespace app\tests\mappers;

use app\tests\{DbManager, MockModel};
use app\mappers\UsersByIdEmailsMapper;
use app\models\UsersModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\UsersByIdEmailsMapper
 */
class UsersByIdEmailsMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_email = 'some@some.com';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод UsersByIdEmailsMapper::getGroup
     */
    public function testGetGroup()
    {
        $usersByLoginMapper = new UsersByIdEmailsMapper([
            'tableName'=>'users',
            'fields'=>['id', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
            'model'=>new UsersModel([
                'id_emails'=>self::$_id,
            ]),
        ]);
        $objectUser = $usersByLoginMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($objectUser));
        $this->assertTrue($objectUser instanceof UsersModel);
        
        $this->assertTrue(property_exists($objectUser, 'name'));
        $this->assertTrue(property_exists($objectUser, 'surname'));
        $this->assertTrue(property_exists($objectUser, 'id_emails'));
        $this->assertTrue(property_exists($objectUser, 'id_phones'));
        $this->assertTrue(property_exists($objectUser, 'id_address'));
        
        $this->assertFalse(empty($objectUser->id));
        $this->assertFalse(empty($objectUser->name));
        $this->assertFalse(empty($objectUser->surname));
        $this->assertFalse(empty($objectUser->id_emails));
        $this->assertFalse(empty($objectUser->id_phones));
        $this->assertFalse(empty($objectUser->id_address));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
