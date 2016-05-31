<?php

namespace app\tests\queries;

use app\queries\SizesForProductQueryCreator;
use app\mappers\SizesForProductMapper;
use app\models\ProductsModel;

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
        $sizesMapper = new SizesForProductMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'orderByField'=>'size',
            'productsModel'=>new ProductsModel(['id'=>1]),
        ]);
        $sizesMapper->visit(new SizesForProductQueryCreator());
        
        $query = 'SELECT [[sizes.id]],[[sizes.size]] FROM {{sizes}} JOIN {{products_sizes}} ON [[sizes.id]]=[[products_sizes.id_sizes]] WHERE [[products_sizes.id_products]]=:id';
        
        $this->assertEquals($query, $sizesMapper->query);
    }
}
