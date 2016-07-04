<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\SubcategoryQueryCreator;

/**
 * Тестирует класс app\queries\SubcategoryQueryCreator
 */
class SubcategoryQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'subcategory',
            'fields'=>['id', 'name'],
        ]);
        
        $queryCreator = new SubcategoryQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[subcategory.id]],[[subcategory.name]] FROM {{subcategory}} JOIN {{categories}} ON [[subcategory.id_categories]]=[[categories.id]] WHERE [[categories.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
