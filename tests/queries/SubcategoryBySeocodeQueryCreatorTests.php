<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\SubcategoryBySeocodeQueryCreator;

/**
 * Тестирует класс app\queries\SubcategoryBySeocodeQueryCreator
 */
class SubcategoryBySeocodeQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new SubcategoryBySeocodeQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[subcategory.id]],[[subcategory.name]],[[subcategory.seocode]],[[subcategory.id_categories]] FROM {{subcategory}} WHERE [[subcategory.seocode]]=:seocode';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
