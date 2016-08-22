<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\CommentsQueryCreator;

/**
 * Тестирует класс app\queries\CommentsQueryCreator
 */
class CommentsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'comments',
            'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
        ]);
        
        $queryCreator = new CommentsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[comments.id]],[[comments.text]],[[comments.name]],[[comments.id_emails]],[[comments.id_products]],[[comments.active]] FROM {{comments}}';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
