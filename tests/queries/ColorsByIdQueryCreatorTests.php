<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ColorsByIdQueryCreator;

/**
 * Тестирует класс app\queries\ColorsByIdQueryCreator
 */
class ColorsByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new ColorsByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[colors.id]],[[colors.color]] FROM {{colors}} WHERE [[colors.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
