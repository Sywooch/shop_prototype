<?php

namespace app\queries;

use app\tests\MockObject;
use app\queries\AddressByAddressQueryCreator;

/**
 * Тестирует класс app\queries\AddressByAddressQueryCreator
 */
class AddressByAddressQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_postcode = '34532';
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'address',
            'fields'=>['id', 'address', 'city', 'country', 'postcode'],
            'params'=>[':postcode'=>self::$_postcode],
        ]);
        
        $queryCreator = new AddressByAddressQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[address.id]],[[address.address]],[[address.city]],[[address.country]],[[address.postcode]] FROM {{address}} WHERE [[address.address]]=:address AND [[address.city]]=:city AND [[address.country]]=:country AND [[address.postcode]]=:postcode';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса
     * при условии отсутствия postcode
     */
    public function testGetInsertQueryWithoutPostcode()
    {
        $mockObject = new MockObject([
            'tableName'=>'address',
            'fields'=>['id', 'address', 'city', 'country', 'postcode'],
            'params'=>[],
        ]);
        
        $queryCreator = new AddressByAddressQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[address.id]],[[address.address]],[[address.city]],[[address.country]],[[address.postcode]] FROM {{address}} WHERE [[address.address]]=:address AND [[address.city]]=:city AND [[address.country]]=:country';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
