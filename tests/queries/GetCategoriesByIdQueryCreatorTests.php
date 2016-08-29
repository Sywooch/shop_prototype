<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\GetCategoriesByIdQueryCreator;

/**
 * Тестирует класс app\queries\GetCategoriesByIdQueryCreator
 */
class GetCategoriesByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 11;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'categories',
            'fields'=>['id', 'name', 'seocode'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new GetCategoriesByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `categories`.`id`, `categories`.`name`, `categories`.`seocode` FROM `categories` WHERE `categories`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
