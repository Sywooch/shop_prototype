<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\CategoriesBySeocodeQueryCreator;

/**
 * Тестирует класс app\queries\CategoriesBySeocodeQueryCreator
 */
class CategoriesBySeocodeQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new CategoriesBySeocodeQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[categories.id]],[[categories.name]],[[categories.seocode]] FROM {{categories}} WHERE [[categories.seocode]]=:seocode';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
