<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\DeliveriesByIdQueryCreator;

/**
 * Тестирует класс app\queries\DeliveriesByIdQueryCreator
 */
class DeliveriesByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new DeliveriesByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[deliveries.id]],[[deliveries.name]],[[deliveries.description]],[[deliveries.price]] FROM {{deliveries}} WHERE [[deliveries.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
