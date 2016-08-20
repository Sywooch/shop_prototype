<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\SizesBySizeQueryCreator;

/**
 * Тестирует класс app\queries\SizesBySizeQueryCreator
 */
class SizesBySizeQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new SizesBySizeQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[sizes.id]],[[sizes.size]] FROM {{sizes}} WHERE [[sizes.size]]=:size';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
