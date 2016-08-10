<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\BrandsForProductQueryCreator;

/**
 * Тестирует класс app\queries\BrandsForProductQueryCreator
 */
class BrandsForProductQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new BrandsForProductQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[brands.id]],[[brands.brand]] FROM {{brands}} JOIN {{products_brands}} ON [[brands.id]]=[[products_brands.id_brands]] WHERE [[products_brands.id_products]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
