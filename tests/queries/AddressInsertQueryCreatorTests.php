<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\AddressInsertQueryCreator;

/**
 * Тестирует класс app\queries\AddressInsertQueryCreator
 */
class AddressInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'address',
            'fields'=>['address', 'city', 'country', 'postcode'],
            'objectsArray'=>[
                new MockModel([
                    'address'=>'Some Address',
                    'city'=>'Some city',
                    'country'=>'Some country',
                    'postcode'=>'5687',
                ]),
            ],
        ]);
        
        $queryCreator = new AddressInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{address}} (address,city,country,postcode) VALUES (:0_address,:0_city,:0_country,:0_postcode)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
