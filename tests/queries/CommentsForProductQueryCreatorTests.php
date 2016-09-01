<?php

namespace app\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\CommentsForProductQueryCreator;

/**
 * Тестирует класс app\queries\CommentsForProductQueryCreator
 */
class CommentsForProductQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 89;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'comments',
            'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new CommentsForProductQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `comments`.`id`, `comments`.`text`, `comments`.`name`, `comments`.`id_emails`, `comments`.`id_products`, `comments`.`active` FROM `comments` WHERE `comments`.`id_products`=" .self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
