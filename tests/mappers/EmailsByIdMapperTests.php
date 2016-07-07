<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\EmailsByIdMapper;
use app\models\EmailsModel;

/**
 * Тестирует класс app\mappers\EmailsByIdMapper
 */
class EmailsByIdMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 231;
    private static $_email = 'some@some.com';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
    }
    
    /**
     * Тестирует метод EmailsByIdMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $emailsByIdMapper = new EmailsByIdMapper([
            'tableName'=>'emails',
            'fields'=>['id', 'email'],
            'model'=>new EmailsModel([
                'id'=>self::$_id,
            ]),
        ]);
        $emailsModel = $emailsByIdMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($emailsModel));
        $this->assertTrue($emailsModel instanceof EmailsModel);
        
        //$this->assertTrue(property_exists($emailsModel, 'id'));
        $this->assertTrue(property_exists($emailsModel, 'email'));
        
        $this->assertTrue(isset($emailsModel->id));
        $this->assertTrue(isset($emailsModel->email));
        
        $this->assertEquals(self::$_id, $emailsModel->id);
        $this->assertEquals(self::$_email, $emailsModel->email);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
