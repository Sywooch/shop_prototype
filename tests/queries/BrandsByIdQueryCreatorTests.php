<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\BrandsByIdQueryCreator;

/**
 * Тестирует класс app\queries\BrandsByIdQueryCreator
 */
class BrandsByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new BrandsByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[brands.id]],[[brands.brand]] FROM {{brands}} WHERE [[brands.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
