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
     * Тестирует метод AddressGetSaveAddressService::handle
     * если пуст AddressGetSaveAddressService::address
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: address
     */
    public function testHandleEmptyName()
    {
        $request = [];
        
        $service = new AddressGetSaveAddressService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод AddressGetSaveAddressService::handle
     * если address уже в СУБД
     */
    public function testHandleExistsAddress()
    {
        $request = ['address'=>self::$dbClass->address['address_1']['address']];
        
        $service = new AddressGetSaveAddressService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(AddressModel::class, $result);
    }
    
    /**
     * Тестирует метод AddressGetSaveAddressService::handle
     * если address еще не в СУБД
     */
    public function testHandleNotExistsAddress()
    {
        $request = ['address'=>'ул. Харона, 15'];
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{address}} WHERE [[address]]=:address')->bindValue(':address', 'ул. Харона, 15')->queryOne();
        
        $this->assertEmpty($result);
        
        $service = new AddressGetSaveAddressService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(AddressModel::class, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{address}} WHERE [[address]]=:address')->bindValue(':address', 'ул. Харона, 15')->queryOne();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals('ул. Харона, 15', $result['address']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
