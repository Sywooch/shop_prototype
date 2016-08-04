<?php

namespace app\queries;

use app\tests\MockObject;
use app\queries\AddressByAddressQueryCreator;

/**
 * Тестирует класс app\queries\AddressByAddressQueryCreator
 */
class AddressByAddressQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_address = 'Some address';
    private static $_city = 'Some city';
    private static $_country = 'Some country';
    private static $_postcode = '34532';
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'address',
            'fields'=>['id', 'address', 'city', 'country', 'postcode'],
            'params'=>[':address'=>self::$_address, ':city'=>self::$_city, ':country'=>self::$_country, ':postcode'=>self::$_postcode],
        ]);
        
        $queryCreator = new AddressByAddressQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[address.id]],[[address.address]],[[address.city]],[[address.country]],[[address.postcode]] FROM {{address}} WHERE [[address.address]]=:address AND [[address.city]]=:city AND [[address.country]]=:country AND [[address.postcode]]=:postcode';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса
     * при условии отсутствия country, postcode
     */
    public function testGetInsertQueryWithoutPostcode()
    {
        $mockObject = new MockObject([
            'tableName'=>'address',
            'fields'=>['id', 'address', 'city', 'country', 'postcode'],
            'params'=>[':address'=>self::$_address, ':city'=>self::$_city],
        ]);
        
        $queryCreator = new AddressByAddressQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[address.id]],[[address.address]],[[address.city]],[[address.country]],[[address.postcode]] FROM {{address}} WHERE [[address.address]]=:address AND [[address.city]]=:city';
        
        $this->assertEquals($query, $mockObject->query);
        
        $this->assertEquals($query, $mockObject->query);
    }
}
