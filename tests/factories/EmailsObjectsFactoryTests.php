<?php

namespace app\tests\factories;

use app\tests\DbManager;
use app\factories\EmailsObjectsFactory;
use app\mappers\EmailsByEmailMapper;
use app\queries\EmailsByEmailQueryCreator;
use app\models\CommentsModel;
use app\models\EmailsModel;

/**
 * Тестирует класс app\factories\EmailsObjectsFactory
 */
class EmailsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод EmailsObjectsFactory::getOne()
     */
    public function testGetOne()
    {
        $commentArray = ['text'=>'Some text', 'name'=>'Some Name', 'email'=>'test@test.com'];
        $commentsModel = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
        $commentsModel->attributes = $commentArray;
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[emails.email]]=:email');
        $command->bindValue(':email', $commentsModel->email);
        $command->execute();
        
        $emailsByEmailMapper = new EmailsByEmailMapper([
            'tableName'=>'emails',
            'fields'=>['id', 'email'],
            'model'=>$commentsModel
        ]);
        
        $this->assertEmpty($emailsByEmailMapper->DbArray);
        $this->assertEmpty($emailsByEmailMapper->objectsArray);
        
        $emailsByEmailMapper->visit(new EmailsByEmailQueryCreator());
        
        $command = \Yii::$app->db->createCommand($emailsByEmailMapper->query);
        $command->bindValues([':email'=>$commentsModel->email]);
        $emailsByEmailMapper->DbArray = $command->queryAll();
        
        $this->assertFalse(empty($emailsByEmailMapper->DbArray));
        
        $emailsByEmailMapper->visit(new EmailsObjectsFactory());
        
        $this->assertFalse(empty($emailsByEmailMapper->objectsArray));
        $this->assertTrue(is_object($emailsByEmailMapper->objectsArray[0]));
        $this->assertTrue($emailsByEmailMapper->objectsArray[0] instanceof EmailsModel);
        
        //$this->assertTrue(property_exists($emailsByEmailMapper->objectsOne, 'id'));
        $this->assertTrue(property_exists($emailsByEmailMapper->objectsArray[0], 'email'));
        
        $this->assertTrue(isset($emailsByEmailMapper->objectsArray[0]->id));
        $this->assertTrue(isset($emailsByEmailMapper->objectsArray[0]->email));
        
        $this->assertEquals($commentArray['email'], $emailsByEmailMapper->objectsArray[0]->email);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
