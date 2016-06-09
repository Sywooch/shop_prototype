<?php

namespace app\tests\mappers;

use app\mappers\CommentsInsertMapper;
use app\tests\DbManager;
use app\models\CommentsModel;

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
        $commentArray = ['text'=>'This a just example text of comment', 'name'=>'Тимофей', 'email'=>'test@test.com', 'id_emails'=>1];
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
        $model->attributes = $commentArray;
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET email=:email');
        $command->bindValue(':email', $commentArray['email']);
        $command->execute();
        
        $commentsInsertMapper = new CommentsInsertMapper([
            'tableName'=>'comments',
            'fields'=>['text', 'name', 'id_emails'],
            'objectsArray'=>[$model]
        ]);
        $result = $commentsInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{comments}} WHERE name=:name');
        $command->bindValue(':name', $commentArray['name']);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('id_emails', $result);
        $this->assertArrayHasKey('active', $result);
        
        $this->assertEquals($commentArray['text'], $result['text']);
        $this->assertEquals($commentArray['name'], $result['name']);
        $this->assertEquals($commentArray['id_emails'], $result['id_emails']);
        $this->assertEquals(0, $result['active']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
