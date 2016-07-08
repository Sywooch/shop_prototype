<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\AddressByIdQueryCreator;

/**
 * Тестирует класс app\queries\AddressByIdQueryCreator
 */
class AddressByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'address',
            'fields'=>['id', 'address', 'city', 'country', 'postcode'],
        ]);
        
        $queryCreator = new AddressByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[address.id]],[[address.address]],[[address.city]],[[address.country]],[[address.postcode]] FROM {{address}} WHERE [[address.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
