<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\AddressInsertMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\AddressInsertMapper
 */
class AddressInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_address = 'Some address';
    private static $_city = 'Some city';
    private static $_country = 'Some country';
    private static $_postcode = '12656';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод AddressInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $emailsInsertMapper = new AddressInsertMapper([
            'tableName'=>'address',
            'fields'=>[ 'address', 'city', 'country', 'postcode'],
            'objectsArray'=>[
                new MockModel([
                    'address'=>self::$_address,
                    'city'=>self::$_city,
                    'country'=>self::$_country,
                    'postcode'=>self::$_postcode,
                ]),
            ],
        ]);
        $result = $emailsInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{address}} WHERE [[address.address]]=:address');
        $command->bindValue(':address', self::$_address);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('address', $result);
        $this->assertArrayHasKey('city', $result);
        $this->assertArrayHasKey('country', $result);
        $this->assertArrayHasKey('postcode', $result);
        
        $this->assertEquals(self::$_address, $result['address']);
        $this->assertEquals(self::$_city, $result['city']);
        $this->assertEquals(self::$_country, $result['country']);
        $this->assertEquals(self::$_postcode, $result['postcode']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
