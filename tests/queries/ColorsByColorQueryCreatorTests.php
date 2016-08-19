<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ColorsByColorQueryCreator;

/**
 * Тестирует класс app\queries\ColorsByColorQueryCreator
 */
class ColorsByColorQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new ColorsByColorQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[colors.id]],[[colors.color]] FROM {{colors}} WHERE [[colors.color]]=:color';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
