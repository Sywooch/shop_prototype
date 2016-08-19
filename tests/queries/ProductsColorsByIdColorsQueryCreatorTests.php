<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductsColorsByIdColorsQueryCreator;

/**
 * Тестирует класс app\queries\ProductsColorsByIdColorsQueryCreator
 */
class ProductsColorsByIdColorsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products_colors',
            'fields'=>['id_products', 'id_colors'],
        ]);
        
        $queryCreator = new ProductsColorsByIdColorsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products_colors.id_products]],[[products_colors.id_colors]] FROM {{products_colors}} WHERE [[products_colors.id_colors]]=:id_colors';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
