<?php

namespace app\tests\queries;

use app\queries\ColorsQueryCreator;
use app\mappers\ColorsMapper;

/**
 * Тестирует класс app\queries\ColorsQueryCreator
 */
class ColorsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $colorsMapper = new ColorsMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'orderByField'=>'color'
        ]);
        $colorsMapper->visit(new ColorsQueryCreator());
        
        $query = 'SELECT [[colors.id]],[[colors.color]] FROM {{colors}}';
        
        $this->assertEquals($query, $colorsMapper->query);
    }
}
