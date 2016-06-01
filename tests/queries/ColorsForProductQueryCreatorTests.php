<?php

namespace app\tests\queries;

use app\queries\ColorsForProductQueryCreator;
use app\mappers\ColorsForProductMapper;
use app\models\ProductsModel;

/**
 * Тестирует класс app\queries\ColorsForProductQueryCreator
 */
class ColorsForProductQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $colorsMapper = new ColorsForProductMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'orderByField'=>'color',
            'model'=>new ProductsModel(['id'=>1]),
        ]);
        $colorsMapper->visit(new ColorsForProductQueryCreator());
        
        $query = 'SELECT [[colors.id]],[[colors.color]] FROM {{colors}} JOIN {{products_colors}} ON [[colors.id]]=[[products_colors.id_colors]] WHERE [[products_colors.id_products]]=:id';
        
        $this->assertEquals($query, $colorsMapper->query);
    }
}
