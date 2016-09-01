<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\CommentsAdminQueryCreator;

/**
 * Тестирует класс app\queries\CommentsAdminQueryCreator
 */
class CommentsAdminQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_filter = false;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'comments',
            'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
        ]);
        
        $queryCreator = new CommentsAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `comments`.`id`, `comments`.`text`, `comments`.`name`, `comments`.`id_emails`, `comments`.`id_products`, `comments`.`active` FROM `comments`";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL запроса
     * при наличии данных \Yii::$app->filters
     */
    public function testGetSelectQueryFilter()
    {
        \Yii::$app->filters->getActive = self::$_filter;
        
        $mockObject = new MockObject([
            'tableName'=>'comments',
            'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
        ]);
        
        $queryCreator = new CommentsAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `comments`.`id`, `comments`.`text`, `comments`.`name`, `comments`.`id_emails`, `comments`.`id_products`, `comments`.`active` FROM `comments` WHERE `comments`.`active`=FALSE";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
