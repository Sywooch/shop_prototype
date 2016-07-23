<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\BrandsQueryCreator;

/**
 * Тестирует класс app\queries\BrandsQueryCreator
 */
class BrandsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
        ]);
        
        $queryCreator = new BrandsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[brands.id]],[[brands.brand]] FROM {{brands}}';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
