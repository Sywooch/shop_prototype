<?php

namespace app\tests\mappers;

use app\mappers\EmailsByEmailMapper;
use app\tests\DbManager;
use app\models\EmailsModel;

/**
 * Тестирует класс app\mappers\EmailsByEmailMapper
 */
class EmailsByEmailMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод EmailsByEmailMapper::getOne
     */
    public function testGetOne()
    {
        $modelArray = ['email'=>'test@test.com'];
        $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
        $emailsModel->attributes = $modelArray;
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[email]]=:email');
        $command->bindValue(':email', $emailsModel->email);
        $command->execute();
        
        $emailsByEmailMapper = new EmailsByEmailMapper([
            'tableName'=>'emails',
            'fields'=>['id', 'email'],
            'model'=>$emailsModel
        ]);
        $emailsModel = $emailsByEmailMapper->getOne();
        
        $this->assertTrue(is_object($emailsModel));
        $this->assertTrue($emailsModel instanceof EmailsModel);
        
        //$this->assertTrue(property_exists($emailsModel, 'id'));
        $this->assertTrue(property_exists($emailsModel, 'email'));
        
        $this->assertTrue(isset($emailsModel->id));
        $this->assertTrue(isset($emailsModel->email));
        $this->assertEquals($modelArray['email'], $emailsModel->email);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
