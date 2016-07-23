<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\SizesQueryCreator;

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
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        
        $queryCreator = new SizesQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[sizes.id]],[[sizes.size]] FROM {{sizes}}';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
