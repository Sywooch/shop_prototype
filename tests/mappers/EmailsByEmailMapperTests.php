<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\EmailsByEmailMapper;
use app\models\EmailsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\EmailsByEmailMapper
 */
class EmailsByEmailMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_email = 'some@some.com';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод EmailsByEmailMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $emailsByEmailMapper = new EmailsByEmailMapper([
            'tableName'=>'emails',
            'fields'=>['id', 'email'],
            'model'=>new EmailsModel([
                'email'=>self::$_email,
            ]),
        ]);
        $emailsModel = $emailsByEmailMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($emailsModel));
        $this->assertTrue($emailsModel instanceof EmailsModel);
        
        //$this->assertTrue(property_exists($emailsModel, 'id'));
        $this->assertTrue(property_exists($emailsModel, 'email'));
        
        $this->assertTrue(isset($emailsModel->id));
        $this->assertTrue(isset($emailsModel->email));
        
        $this->assertEquals(self::$_email, $emailsModel->email);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
