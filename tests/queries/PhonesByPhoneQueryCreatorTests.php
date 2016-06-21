<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\PhonesByPhoneQueryCreator;

/**
 * Тестирует класс app\queries\PhonesByPhoneQueryCreator
 */
class PhonesByPhoneQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new PhonesByPhoneQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[phones.id]],[[phones.phone]] FROM {{phones}} WHERE [[phones.phone]]=:phone';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
