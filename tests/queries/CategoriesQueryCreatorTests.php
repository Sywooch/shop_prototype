<?php

namespace app\tests\queries;

use app\queries\CategoriesQueryCreator;
use app\mappers\CategoriesMapper;

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
        $categoriesMapper = new CategoriesMapper(['tableName'=>'categories', 'fields'=>['id', 'name']]);
        $categoriesMapper->visit(new CategoriesQueryCreator());
        
        $query = 'SELECT [[categories.id]],[[categories.name]] FROM {{categories}}';
        
        $this->assertEquals($query, $categoriesMapper->query);
    }
}
