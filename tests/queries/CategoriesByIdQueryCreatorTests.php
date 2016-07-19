<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\CategoriesByIdQueryCreator;

/**
 * Тестирует класс app\queries\CategoriesByIdQueryCreator
 */
class CategoriesByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'categories',
            'fields'=>['id', 'name', 'seocode'],
        ]);
        
        $queryCreator = new CategoriesByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[categories.id]],[[categories.name]],[[categories.seocode]] FROM {{categories}} WHERE [[categories.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
