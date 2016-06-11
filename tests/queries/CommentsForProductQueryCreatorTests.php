<?php

namespace app\queries;

use app\mappers\CommentsForProductMapper;
use app\queries\CommentsForProductQueryCreator;
use app\models\ProductsModel;

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
        $commentsForProductMapper = new CommentsForProductMapper([
            'tableName'=>'comments',
            'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
            'model'=>new ProductsModel(['id'=>1]),
        ]);
        $commentsForProductMapper->visit(new CommentsForProductQueryCreator());
        
        $query = 'SELECT [[comments.id]],[[comments.text]],[[comments.name]],[[comments.id_emails]],[[comments.id_products]],[[comments.active]] FROM {{comments}} WHERE [[comments.id_products]]=:id_products';
        
        $this->assertEquals($query, $commentsForProductMapper->query);
    }
}
