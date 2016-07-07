<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\PhonesByIdQueryCreator;

/**
 * Тестирует класс app\queries\PhonesByIdQueryCreator
 */
class PhonesByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'phones',
            'fields'=>['id', 'phone'],
        ]);
        
        $queryCreator = new PhonesByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[phones.id]],[[phones.phone]] FROM {{phones}} WHERE [[phones.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
