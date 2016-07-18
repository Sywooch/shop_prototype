<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\AddressByIdMapper;
use app\models\AddressModel;

/**
 * Тестирует класс app\mappers\AddressByIdMapper
 */
class AddressByIdMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 231;
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
    }
    
    /**
     * Тестирует метод AddressByIdMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $addressByIdMapper = new AddressByIdMapper([
            'tableName'=>'address',
            'fields'=>['id', 'address', 'city', 'country', 'postcode'],
            'model'=>new AddressModel([
                'id'=>self::$_id,
            ]),
        ]);
        $addressModel = $addressByIdMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($addressModel));
        $this->assertTrue($addressModel instanceof AddressModel);
        
        //$this->assertTrue(property_exists($AddressModel, 'id'));
        $this->assertTrue(property_exists($addressModel, 'address'));
        $this->assertTrue(property_exists($addressModel, 'city'));
        $this->assertTrue(property_exists($addressModel, 'country'));
        $this->assertTrue(property_exists($addressModel, 'postcode'));
        
        $this->assertFalse(empty($addressModel->id));
        $this->assertFalse(empty($addressModel->address));
        $this->assertFalse(empty($addressModel->city));
        $this->assertFalse(empty($addressModel->country));
        $this->assertFalse(empty($addressModel->postcode));
        
        $this->assertEquals(self::$_id, $addressModel->id);
        $this->assertEquals(self::$_address, $addressModel->address);
        $this->assertEquals(self::$_city, $addressModel->city);
        $this->assertEquals(self::$_country, $addressModel->country);
        $this->assertEquals(self::$_postcode, $addressModel->postcode);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
