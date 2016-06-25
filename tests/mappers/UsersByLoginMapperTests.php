<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\UsersByLoginMapper;
use app\models\UsersModel;

/**
 * Тестирует класс app\mappers\UsersByLoginMapper
 */
class UsersByLoginMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_login = 'Somelogin';
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_email = 'some@some.com';
    private static $_phone = '+396548971203';
    private static $_address = 'Some Address';
    private static $_city = 'Some city';
    private static $_country = 'Some country';
    private static $_postcode = 'F12345';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{phones}} SET [[id]]=:id, [[phone]]=:phone');
        $command->bindValues([':id'=>self::$_id, ':phone'=>self::$_phone]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{address}} SET [[id]]=:id, [[address]]=:address, [[city]]=:city, [[country]]=:country, [[postcode]]=:postcode');
        $command->bindValues([':id'=>self::$_id, ':address'=>self::$_address, ':city'=>self::$_city, ':country'=>self::$_country, ':postcode'=>self::$_postcode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует метод UsersByLoginMapper::getGroup
     */
    public function testGetGroup()
    {
        $usersByLoginMapper = new UsersByLoginMapper([
            'tableName'=>'users',
            'fields'=>['id', 'login', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
            'model'=>new MockModel([
                'login'=>self::$_login,
            ]),
        ]);
        $objectUser = $usersByLoginMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($objectUser));
        $this->assertTrue($objectUser instanceof UsersModel);
        
        //$this->assertTrue(property_exists($objectUser, 'login'));
        //$this->assertTrue(property_exists($objectUser, 'password'));
        $this->assertTrue(property_exists($objectUser, 'name'));
        $this->assertTrue(property_exists($objectUser, 'surname'));
        $this->assertTrue(property_exists($objectUser, 'id_emails'));
        $this->assertTrue(property_exists($objectUser, 'id_phones'));
        $this->assertTrue(property_exists($objectUser, 'id_address'));
        
        $this->assertTrue(isset($objectUser->id));
        $this->assertTrue(isset($objectUser->login));
        $this->assertTrue(isset($objectUser->name));
        $this->assertTrue(isset($objectUser->surname));
        $this->assertTrue(isset($objectUser->id_emails));
        $this->assertTrue(isset($objectUser->id_phones));
        $this->assertTrue(isset($objectUser->id_address));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
