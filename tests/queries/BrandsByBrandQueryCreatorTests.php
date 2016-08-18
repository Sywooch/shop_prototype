<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\BrandsByBrandQueryCreator;

/**
 * Тестирует класс app\queries\BrandsByBrandQueryCreator
 */
class BrandsByBrandQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new BrandsByBrandQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[brands.id]],[[brands.brand]] FROM {{brands}} WHERE [[brands.brand]]=:brand';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
