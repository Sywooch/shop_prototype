<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\SizesForProductQueryCreator;

/**
 * Тестирует класс app\queries\SizesForProductQueryCreator
 */
class SizesForProductQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        
        $queryCreator = new SizesForProductQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[sizes.id]],[[sizes.size]] FROM {{sizes}} JOIN {{products_sizes}} ON [[sizes.id]]=[[products_sizes.id_sizes]] WHERE [[products_sizes.id_products]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
