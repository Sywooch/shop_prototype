<?php

namespace app\tests\queries;

use app\queries\EmailsByCommentsQueryCreator;
use app\mappers\EmailsByCommentsMapper;
use app\models\CommentsModel;

/**
 * Тестирует класс app\queries\EmailsByCommentsQueryCreator
 */
class EmailsByCommentsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $commentArray = ['text'=>'Some text', 'name'=>'Some Name', 'email'=>'test@test.com'];
        $commentsModel = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
        $commentsModel->attributes = $commentArray;
        
        $emailsByCommentsMapper = new EmailsByCommentsMapper([
            'tableName'=>'emails',
            'fields'=>['id'],
            'model'=>$commentsModel
        ]);
        $emailsByCommentsMapper->visit(new EmailsByCommentsQueryCreator());
        
        $query = 'SELECT [[emails.id]] FROM {{emails}} WHERE [[emails.email]]=:email';
        
        $this->assertEquals($query, $emailsByCommentsMapper->query);
    }
}
