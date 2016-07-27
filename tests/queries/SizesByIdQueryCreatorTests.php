<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\SizesByIdQueryCreator;

/**
 * Тестирует класс app\queries\SizesByIdQueryCreator
 */
class SizesByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new SizesByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[sizes.id]],[[sizes.size]] FROM {{sizes}} WHERE [[sizes.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
