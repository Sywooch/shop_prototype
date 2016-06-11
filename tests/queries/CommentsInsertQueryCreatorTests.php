<?php

namespace app\queries;

use app\mappers\CommentsInsertMapper;
use app\queries\CommentsInsertQueryCreator;
use app\models\CommentsModel;
use app\tests\DbManager;

/**
 * Тестирует класс app\queries\CommentsInsertQueryCreator
 */
class CommentsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
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
        
        $commentsInsertMapper->visit(new CommentsInsertQueryCreator());
        
        $query = 'INSERT INTO {{comments}} (text,name,id_emails,id_products) VALUES (:0_text,:0_name,:0_id_emails,:0_id_products)';
        
        $this->assertEquals($query, $commentsInsertMapper->query);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
