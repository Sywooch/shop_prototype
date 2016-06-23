<?php

namespace app\queries;

use app\tests\MockObject;
use app\queries\DeliveriesQueryCreator;

/**
 * Тестирует класс app\queries\DeliveriesQueryCreator
 */
class DeliveriesQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'deliveries',
            'fields'=>['id', 'name', 'description', 'price'],
        ]);
        
        $queryCreator = new DeliveriesQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[deliveries.id]],[[deliveries.name]],[[deliveries.description]],[[deliveries.price]] FROM {{deliveries}}';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
