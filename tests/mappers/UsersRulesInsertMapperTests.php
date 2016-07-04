<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\UsersRulesInsertMapper;
use app\models\UsersModel;

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
    private static $_email = 'some@some.com';
    private static $_phone = '+396548971203';
    private static $_address = 'Some Address';
    private static $_city = 'Some city';
    private static $_country = 'Some country';
    private static $_postcode = 'F12345';
    private static $_rule = 'Some Rule';
    private static $_rule2 = 'Some Rule Two';
    
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
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
            'model'=>new UsersModel([
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
