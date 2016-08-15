<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\SubcategoryByNameQueryCreator;

/**
 * Тестирует класс app\queries\SubcategoryByNameQueryCreator
 */
class SubcategoryByNameQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'subcategory',
            'fields'=>['id', 'name', 'seocode', 'id_categories'],
        ]);
        
        $queryCreator = new SubcategoryByNameQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[subcategory.id]],[[subcategory.name]],[[subcategory.seocode]],[[subcategory.id_categories]] FROM {{subcategory}} WHERE [[subcategory.name]]=:name';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
