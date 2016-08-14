<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\CategoriesByNameQueryCreator;

/**
 * Тестирует класс app\queries\CategoriesByNameQueryCreator
 */
class CategoriesByNameQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new CategoriesByNameQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[categories.id]],[[categories.name]],[[categories.seocode]] FROM {{categories}} WHERE [[categories.name]]=:name';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
