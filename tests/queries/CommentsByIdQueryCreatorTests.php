<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\CommentsByIdQueryCreator;

/**
 * Тестирует класс app\queries\CommentsByIdQueryCreator
 */
class CommentsByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'comments',
            'fields'=>['id', 'date', 'text', 'name', 'id_emails', 'id_products', 'active'],
        ]);
        
        $queryCreator = new CommentsByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[comments.id]],[[comments.date]],[[comments.text]],[[comments.name]],[[comments.id_emails]],[[comments.id_products]],[[comments.active]] FROM {{comments}} WHERE [[comments.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
