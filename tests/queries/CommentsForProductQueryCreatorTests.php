<?php

namespace app\queries;

use app\tests\MockObject;
use app\queries\CommentsForProductQueryCreator;

/**
 * Тестирует класс app\queries\CommentsForProductQueryCreator
 */
class CommentsForProductQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new CommentsForProductQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[comments.id]],[[comments.text]],[[comments.name]],[[comments.id_emails]],[[comments.id_products]],[[comments.active]] FROM {{comments}} WHERE [[comments.id_products]]=:id_products';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
