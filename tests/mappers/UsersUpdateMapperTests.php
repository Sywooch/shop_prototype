<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\UsersUpdateMapper;

/**
 * Тестирует класс app\mappers\UsersUpdateMapper
 */
class UsersUpdateMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_name2 = 'Second some name';
    private static $_surname = 'Some surname';
    private static $_surname2 = 'Second some surname';
    private static $_idEmails = 3;
    private static $_idEmails2 = 12;
    private static $_idPhones = 5;
    private static $_idPhones2 = 8;
    private static $_idAddress = 1;
    private static $_idAddress2 = 32;
    private static $_email = 'some@some.com';
    private static $_email2 = 'some2@some.com';
    private static $_phone = '+396548971203';
    private static $_phone2 = '+56548971203';
    private static $_address = 'Some Address';
    private static $_city = 'Some city';
    private static $_country = 'Some country';
    private static $_postcode = 'F12345';
    private static $_address2 = 'Some Address 2';
    private static $_city2 = 'Some city 2';
    private static $_country2 = 'Some country 2';
    private static $_postcode2 = '4568';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} (id,email) VALUE (:id0,:email0), (:id1,:email1)');
        $command->bindValues([':id0'=>self::$_idEmails, ':email0'=>self::$_email, ':id1'=>self::$_idEmails2, ':email1'=>self::$_email2]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{phones}} (id,phone) VALUE (:id0,:phone0), (:id1,:phone1)');
        $command->bindValues([':id0'=>self::$_idPhones, ':phone0'=>self::$_phone, ':id1'=>self::$_idPhones2, ':phone1'=>self::$_phone2]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{address}} (id,address,city,country,postcode) VALUE (:id0,:address0,:city0,:country0,:postcode0), (:id1,:address1,:city1,:country1,:postcode1)');
        $command->bindValues([':id0'=>self::$_idAddress, ':address0'=>self::$_address, ':city0'=>self::$_city, ':country0'=>self::$_country, ':postcode0'=>self::$_postcode, ':id1'=>self::$_idAddress2, ':address1'=>self::$_address2, ':city1'=>self::$_city2, ':country1'=>self::$_country2, ':postcode1'=>self::$_postcode2]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_idEmails, ':id_phones'=>self::$_idPhones, ':id_address'=>self::$_idAddress]);
        $command->execute();
    }
    
    /**
     * Тестирует метод UsersUpdateMapper::setGroup
     */
    public function testSetGroup()
    {
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{users}} WHERE [[id]]=:id');
        $command->bindValue(':id', self::$_id);
        $result = $command->queryOne();
        
        $this->assertFalse(empty($result));
        $this->assertTrue(array_key_exists('name', $result));
        $this->assertTrue(array_key_exists('surname', $result));
        $this->assertTrue(array_key_exists('id_emails', $result));
        $this->assertTrue(array_key_exists('id_phones', $result));
        $this->assertTrue(array_key_exists('id_address', $result));
        
        $this->assertEquals(self::$_name, $result['name']);
        $this->assertEquals(self::$_surname, $result['surname']);
        $this->assertEquals(self::$_idEmails, $result['id_emails']);
        $this->assertEquals(self::$_idPhones, $result['id_phones']);
        $this->assertEquals(self::$_idAddress, $result['id_address']);
        
        $usersUpdateMapper = new UsersUpdateMapper([
            'tableName'=>'users',
            'fields'=>['name', 'surname', 'id_emails', 'id_phones', 'id_address'],
            'model'=> new MockModel([
                'id'=>self::$_id,
                'name'=>self::$_name2,
                'surname'=>self::$_surname2,
                'id_emails'=>self::$_idEmails2,
                'id_phones'=>self::$_idPhones2,
                'id_address'=>self::$_idAddress2,
            ]),
        ]);
        $result = $usersUpdateMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{users}} WHERE [[id]]=:id');
        $command->bindValue(':id', self::$_id);
        $result = $command->queryOne();
        
        $this->assertFalse(empty($result));
        $this->assertTrue(array_key_exists('name', $result));
        $this->assertTrue(array_key_exists('surname', $result));
        $this->assertTrue(array_key_exists('id_emails', $result));
        $this->assertTrue(array_key_exists('id_phones', $result));
        $this->assertTrue(array_key_exists('id_address', $result));
        
        $this->assertEquals(self::$_name2, $result['name']);
        $this->assertEquals(self::$_surname2, $result['surname']);
        $this->assertEquals(self::$_idEmails2, $result['id_emails']);
        $this->assertEquals(self::$_idPhones2, $result['id_phones']);
        $this->assertEquals(self::$_idAddress2, $result['id_address']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
