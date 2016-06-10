<?php

namespace app\tests\mappers;

use app\mappers\EmailsByCommentsMapper;
use app\tests\DbManager;
use app\models\CommentsModel;
use app\models\EmailsModel;

/**
 * Тестирует класс app\mappers\EmailsByCommentsMapper
 */
class EmailsByCommentsMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод EmailsByCommentsMapper::getOne
     */
    public function testGetOne()
    {
        $commentArray = ['text'=>'Some text', 'name'=>'Some Name', 'email'=>'test@test.com'];
        $commentsModel = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
        $commentsModel->attributes = $commentArray;
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[emails.email]]=:email');
        $command->bindValue(':email', $commentsModel->email);
        $command->execute();
        
        $emailsByCommentsMapper = new EmailsByCommentsMapper([
            'tableName'=>'emails',
            'fields'=>['id', 'email'],
            'model'=>$commentsModel
        ]);
        $emailsModel = $emailsByCommentsMapper->getOne();
        
        $this->assertTrue(is_object($emailsModel));
        $this->assertTrue($emailsModel instanceof EmailsModel);
        
        //$this->assertTrue(property_exists($emailsModel, 'id'));
        $this->assertTrue(property_exists($emailsModel, 'email'));
        $this->assertTrue(isset($emailsModel->id));
        $this->assertTrue(isset($emailsModel->email));
        $this->assertEquals($commentArray['email'], $emailsModel->email);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
