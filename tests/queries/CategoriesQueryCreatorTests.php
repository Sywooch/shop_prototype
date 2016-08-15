<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\CategoriesQueryCreator;

/**
 * Тестирует класс app\queries\CategoriesQueryCreator
 */
class CategoriesQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new CategoriesQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[categories.id]],[[categories.name]],[[categories.seocode]] FROM {{categories}}';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
