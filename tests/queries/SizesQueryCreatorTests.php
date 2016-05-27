<?php

namespace app\tests\queries;

use app\queries\SizesQueryCreator;
use app\mappers\SizesMapper;

/**
 * Тестирует класс app\queries\SizesQueryCreator
 */
class SizesQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $sizesMapper = new SizesMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'orderByField'=>'size'
        ]);
        $sizesMapper->visit(new SizesQueryCreator());
        
        $query = 'SELECT [[sizes.id]],[[sizes.size]] FROM {{sizes}}';
        
        $this->assertEquals($query, $sizesMapper->query);
    }
}
