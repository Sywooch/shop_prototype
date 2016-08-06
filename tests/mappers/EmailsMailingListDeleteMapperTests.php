<?php

namespace app\tests\mappers;

use app\tests\{DbManager, MockModel};
use app\mappers\EmailsMailingListDeleteMapper;
use app\helpers\MappersHelper;
use app\models\EmailsMailingListModel;

/**
 * Тестирует класс app\mappers\EmailsMailingListDeleteMapper
 */
class EmailsMailingListDeleteMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_id2 = 2;
    private static $_email = 'some@some.com';
    private static $_name = 'some name';
    private static $_name2 = 'some name 2';
    private static $_description = 'some description';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{mailing_list}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{mailing_list}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description');
        $command->bindValues([':id'=>self::$_id2, ':name'=>self::$_name2, ':description'=>self::$_description]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails_mailing_list}} SET [[id_email]]=:id_email, [[id_mailing_list]]=:id_mailing_list');
        $command->bindValues([':id_email'=>self::$_id, ':id_mailing_list'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails_mailing_list}} SET [[id_email]]=:id_email, [[id_mailing_list]]=:id_mailing_list');
        $command->bindValues([':id_email'=>self::$_id, ':id_mailing_list'=>self::$_id2]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод EmailsMailingListDeleteMapper::setGroup
     */
    public function testSetGroup()
    {
        $this->assertFalse(empty($result = \Yii::$app->db->createCommand('SELECT * FROM {{emails_mailing_list}}')->queryAll()));
        $this->assertEquals(2, count($result));
        
        $emailsMailingListDeleteMapper = new EmailsMailingListDeleteMapper([
            'tableName'=>'emails_mailing_list',
            'fields'=>['id_email', 'id_mailing_list'],
            'objectsArray'=>[
                new EmailsMailingListModel(['id_email'=>self::$_id, 'id_mailing_list'=>self::$_id]),
                new EmailsMailingListModel(['id_email'=>self::$_id, 'id_mailing_list'=>self::$_id2]),
            ],
        ]);
        
        $result = $emailsMailingListDeleteMapper->setGroup();
        
        $this->assertEquals(2, $result);
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{emails_mailing_list}}')->queryAll()));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
