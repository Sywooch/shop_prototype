<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\MailingListForEmailMapper;
use app\models\{MailingListModel, 
    EmailsModel};
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\MailingListForEmailMapper
 */
class MailingListForEmailMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'some name';
    private static $_description = 'some description';
    private static $_email = 'some@some.com';
    
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails_mailing_list}} SET [[id_email]]=:id_email, [[id_mailing_list]]=:id_mailing_list');
        $command->bindValues([':id_email'=>self::$_id, ':id_mailing_list'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод MailingListForEmailMapper::getGroup
     */
    public function testGetGroup()
    {
        $mailingListForEmailMapper = new MailingListForEmailMapper([
            'tableName'=>'mailing_list',
            'fields'=>['id', 'name', 'description'],
            'model'=>new EmailsModel([
                'email'=>self::$_email,
            ]),
        ]);
        $mailingList = $mailingListForEmailMapper->getGroup();
        
        $this->assertTrue(is_array($mailingList));
        $this->assertFalse(empty($mailingList));
        $this->assertTrue(is_object($mailingList[0]));
        $this->assertTrue($mailingList[0] instanceof MailingListModel);
        
        $this->assertTrue(property_exists($mailingList[0], 'id'));
        $this->assertTrue(property_exists($mailingList[0], 'name'));
        $this->assertTrue(property_exists($mailingList[0], 'description'));
        
        $this->assertFalse(empty($mailingList[0]->id));
        $this->assertFalse(empty($mailingList[0]->name));
        $this->assertFalse(empty($mailingList[0]->description));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
