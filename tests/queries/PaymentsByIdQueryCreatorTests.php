<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\PaymentsByIdQueryCreator;

/**
 * Тестирует класс app\queries\PaymentsByIdQueryCreator
 */
class PaymentsByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'payments',
            'fields'=>['id', 'name', 'description'],
        ]);
        
        $queryCreator = new PaymentsByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[payments.id]],[[payments.name]],[[payments.description]] FROM {{payments}} WHERE [[payments.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
