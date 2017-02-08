<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AddressGetSaveAddressService;
use app\tests\DbManager;
use app\tests\sources\fixtures\AddressFixture;
use app\models\AddressModel;

/**
 * Тестирует класс AddressGetSaveAddressService
 */
class AddressGetSaveAddressServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'address'=>AddressFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AddressGetSaveAddressService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AddressGetSaveAddressService::class);
        
        $this->assertTrue($reflection->hasProperty('addressModel'));
        $this->assertTrue($reflection->hasProperty('address'));
    }
    
    /**
     * Тестирует метод AddressGetSaveAddressService::getAddress
     */
    public function testGetAddress()
    {
        $service = new AddressGetSaveAddressService();
        
        $reflection = new \ReflectionProperty($service, 'address');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$dbClass->address['address_1']['address']);
        
        $reflection = new \ReflectionMethod($service, 'getAddress');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInstanceOf(AddressModel::class, $result);
    }
    
    /**
     * Тестирует метод AddressGetSaveAddressService::setAddress
     * передаю неверный тип параметра
     * @expectedException TypeError
     */
    public function testSetAddressError()
    {
        $service = new AddressGetSaveAddressService();
        $service->setAddress([]);
    }
    
    /**
     * Тестирует метод AddressGetSaveAddressService::setAddress
     */
    public function testSetAddress()
    {
        $service = new AddressGetSaveAddressService();
        $service->setAddress('Address');
        
        $reflection = new \ReflectionProperty($service, 'address');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($service);
        
        $this->assertEquals('Address', $result);
    }
    
    /**
     * Тестирует метод AddressGetSaveAddressService::get
     * если пуст AddressGetSaveAddressService::address
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: address
     */
    public function testGetEmptyName()
    {
        $service = new AddressGetSaveAddressService();
        $service->get();
    }
    
    /**
     * Тестирует метод AddressGetSaveAddressService::get
     * если address уже в СУБД
     */
    public function testGetExistsAddress()
    {
        $service = new AddressGetSaveAddressService();
        
        $reflection = new \ReflectionProperty($service, 'address');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$dbClass->address['address_1']['address']);
        
        $result = $service->get();
        
        $this->assertInstanceOf(AddressModel::class, $result);
    }
    
    /**
     * Тестирует метод AddressGetSaveAddressService::get
     * если address еще не в СУБД
     */
    public function testGetNotExistsAddress()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{address}} WHERE [[address]]=:address')->bindValue(':address', 'New Address')->queryOne();
        $this->assertEmpty($result);
        
        $service = new AddressGetSaveAddressService();
        
        $reflection = new \ReflectionProperty($service, 'address');
        $reflection->setAccessible(true);
        $reflection->setValue($service, 'New Address');
        
        $result = $service->get();
        
        $this->assertInstanceOf(AddressModel::class, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{address}} WHERE [[address]]=:address')->bindValue(':address', 'New Address')->queryOne();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals('New Address', $result['address']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
