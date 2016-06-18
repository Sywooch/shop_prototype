<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ColorsForProductQueryCreator;

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
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new ColorsForProductQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[colors.id]],[[colors.color]] FROM {{colors}} JOIN {{products_colors}} ON [[colors.id]]=[[products_colors.id_colors]] WHERE [[products_colors.id_products]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
