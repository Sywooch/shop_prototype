<?php

namespace app\tests\factories;

use app\factories\EmailsOneObjectFactory;
use app\tests\DbManager;
use app\mappers\EmailsByCommentsMapper;
use app\queries\EmailsByCommentsQueryCreator;
use app\models\CommentsModel;
use app\models\EmailsModel;

/**
 * Тестирует класс app\factories\EmailsOneObjectFactory
 */
class EmailsOneObjectFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод EmailsOneObjectFactory::getOne()
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
        
        $this->assertFalse(isset($emailsByCommentsMapper->objectsOne));
        $this->assertEmpty($emailsByCommentsMapper->DbArray);
        
        $emailsByCommentsMapper->visit(new EmailsByCommentsQueryCreator());
        
        $command = \Yii::$app->db->createCommand($emailsByCommentsMapper->query);
        $command->bindValue(':email', $commentsModel->email);
        $emailsByCommentsMapper->DbArray = $command->queryOne();
        
        $this->assertFalse(empty($emailsByCommentsMapper->DbArray));
        
        $emailsByCommentsMapper->visit(new EmailsOneObjectFactory());
        
        $this->assertTrue(isset($emailsByCommentsMapper->objectsOne));
        $this->assertTrue(is_object($emailsByCommentsMapper->objectsOne));
        $this->assertTrue($emailsByCommentsMapper->objectsOne instanceof EmailsModel);
        
        //$this->assertTrue(property_exists($emailsByCommentsMapper->objectsOne, 'id'));
        $this->assertTrue(property_exists($emailsByCommentsMapper->objectsOne, 'email'));
        
        $this->assertTrue(isset($emailsByCommentsMapper->objectsOne->id));
        $this->assertTrue(isset($emailsByCommentsMapper->objectsOne->email));
        
        $this->assertEquals($commentArray['email'], $emailsByCommentsMapper->objectsOne->email);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
