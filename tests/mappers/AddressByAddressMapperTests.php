<?php

namespace app\tests\mappers;

use app\tests\{DbManager, MockModel};
use app\mappers\AddressByAddressMapper;
use app\models\AddressModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\AddressByAddressMapper
 */
class AddressByAddressMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_address = 'Some Address';
    private static $_city = 'Some city';
    private static $_country = 'Some country';
    private static $_postcode = 'F12345';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{address}} SET [[id]]=:id, [[address]]=:address, [[city]]=:city, [[country]]=:country, [[postcode]]=:postcode');
        $command->bindValues([':id'=>self::$_id, ':address'=>self::$_address, ':city'=>self::$_city, ':country'=>self::$_country, ':postcode'=>self::$_postcode]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод AddressByAddressMapper::getGroup
     */
    public function testGetGroup()
    {
        $addressByAddressMapper = new AddressByAddressMapper([
            'tableName'=>'address',
            'fields'=>['id', 'address', 'city', 'country', 'postcode'],
            'model'=>new AddressModel([
                'address'=>self::$_address,
                'city'=>self::$_city,
                'country'=>self::$_country,
                'postcode'=>self::$_postcode,
            ]),
        ]);
        $objectAddress = $addressByAddressMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($objectAddress));
        $this->assertTrue($objectAddress instanceof AddressModel);
        
        //$this->assertTrue(property_exists($objectAddress, 'id'));
        $this->assertTrue(property_exists($objectAddress, 'address'));
        $this->assertTrue(property_exists($objectAddress, 'city'));
        $this->assertTrue(property_exists($objectAddress, 'country'));
        $this->assertTrue(property_exists($objectAddress, 'postcode'));
        
        $this->assertTrue(isset($objectAddress->id));
        $this->assertTrue(isset($objectAddress->address));
        $this->assertTrue(isset($objectAddress->city));
        $this->assertTrue(isset($objectAddress->country));
        $this->assertTrue(isset($objectAddress->postcode));
    }
    
    /**
     * Тестирует метод AddressByAddressMapper::getGroup
     * при условии отсутствия postcode
     */
    public function testGetGroupWithoutPostcode()
    {
        $addressByAddressMapper = new AddressByAddressMapper([
            'tableName'=>'address',
            'fields'=>['id', 'address', 'city', 'country', 'postcode'],
            'model'=>new AddressModel([
                'address'=>self::$_address,
                'city'=>self::$_city,
                'country'=>self::$_country,
            ]),
        ]);
        $objectAddress = $addressByAddressMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($objectAddress));
        $this->assertTrue($objectAddress instanceof AddressModel);
        
        //$this->assertTrue(property_exists($objectAddress, 'id'));
        $this->assertTrue(property_exists($objectAddress, 'address'));
        $this->assertTrue(property_exists($objectAddress, 'city'));
        $this->assertTrue(property_exists($objectAddress, 'country'));
        $this->assertTrue(property_exists($objectAddress, 'postcode'));
        
        $this->assertTrue(isset($objectAddress->id));
        $this->assertTrue(isset($objectAddress->address));
        $this->assertTrue(isset($objectAddress->city));
        $this->assertTrue(isset($objectAddress->country));
        $this->assertTrue(isset($objectAddress->postcode));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
