<?php

namespace app\queries;

use app\mappers\CommentsInsertMapper;
use app\queries\CommentsInsertQueryCreator;
use app\models\CommentsModel;

/**
 * Тестирует класс app\queries\CommentsInsertQueryCreator
 */
class CommentsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $commentsInsertMapper = new CommentsInsertMapper([
            'tableName'=>'comments',
            'fields'=>['text', 'name', 'id_emails'],
            'objectsArray'=>[new CommentsModel(['text'=>'This a just example text of comment', 'name'=>'Тимофей', 'id_emails'=>1])],
        ]);
        $commentsInsertMapper->visit(new CommentsInsertQueryCreator());
        
        $query = 'INSERT INTO {{comments}} (text,name,id_emails) VALUES (:0_text,:0_name,:0_id_emails)';
        
        $this->assertEquals($query, $commentsInsertMapper->query);
    }
}
