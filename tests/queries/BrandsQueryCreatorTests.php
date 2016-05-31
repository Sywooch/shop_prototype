<?php

namespace app\tests\queries;

use app\queries\BrandsQueryCreator;
use app\mappers\BrandsMapper;

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
        $brandsMapper = new BrandsMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'orderByField'=>'brand'
        ]);
        $brandsMapper->visit(new BrandsQueryCreator());
        
        $query = 'SELECT [[brands.id]],[[brands.brand]] FROM {{brands}}';
        
        $this->assertEquals($query, $brandsMapper->query);
    }
}
