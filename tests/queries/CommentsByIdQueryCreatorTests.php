<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\CommentsByIdQueryCreator;

/**
 * Тестирует класс app\queries\CommentsByIdQueryCreator
 */
class CommentsByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 31;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'comments',
            'fields'=>['id', 'date', 'text', 'name', 'id_emails', 'id_products', 'active'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new CommentsByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `comments`.`id`, `comments`.`date`, `comments`.`text`, `comments`.`name`, `comments`.`id_emails`, `comments`.`id_products`, `comments`.`active` FROM `comments` WHERE `comments`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
