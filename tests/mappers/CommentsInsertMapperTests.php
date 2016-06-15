<?php

namespace app\tests\mappers;

use app\mappers\CommentsInsertMapper;
use app\tests\DbManager;
use app\models\CommentsModel;
use app\mappers\EmailsByEmailMapper;

/**
 * Тестирует класс app\mappers\CommentsInsertMapper
 */
class CommentsInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод CommentsInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $commentsArray = ['text'=>'This a just example text of comment', 'name'=>'Тимофей', 'email'=>'test@test.com', 'id_products'=>12];
        $commentsModel = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
        $commentsModel->attributes = $commentsArray;
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[emails.email]]=:email');
        $command->bindValue(':email', $commentsModel->email);
        $command->execute();
        
        $commentsInsertMapper = new CommentsInsertMapper([
            'tableName'=>'comments',
            'fields'=>['text', 'name', 'id_emails', 'id_products'],
            'objectsArray'=>[$commentsModel],
        ]);
        
        $result = $commentsInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{comments}} WHERE [[comments.name]]=:name');
        $command->bindValue(':name', $commentsArray['name']);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('id_emails', $result);
        $this->assertArrayHasKey('id_products', $result);
        $this->assertArrayHasKey('active', $result);
        
        $this->assertEquals($commentsArray['text'], $result['text']);
        $this->assertEquals($commentsArray['name'], $result['name']);
        $this->assertEquals($commentsArray['id_products'], $result['id_products']);
        $this->assertEquals(0, $result['active']);
        
        $emailsByCommentsMapper = new EmailsByEmailMapper([
            'tableName'=>'emails',
            'fields'=>['id'],
            'model'=>$commentsModel
        ]);
        $emailsModel = $emailsByCommentsMapper->getOne();
        
        $this->assertEquals($emailsModel->id, $result['id_emails']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
