<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\AddressAddressFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\AddressFixture;
use app\models\AddressModel;

/**
 * Тестирует класс AddressAddressFinder
 */
class AddressAddressFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'address'=>AddressFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AddressAddressFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AddressAddressFinder::class);
        
        $this->assertTrue($reflection->hasProperty('address'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод AddressAddressFinder::setAddress
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetAddressError()
    {
        $address = null;
        
        $widget = new AddressAddressFinder();
        $widget->setAddress($address);
    }
    
    /**
     * Тестирует метод AddressAddressFinder::setAddress
     */
    public function testSetAddress()
    {
        $address = 'Address';
        
        $widget = new AddressAddressFinder();
        $widget->setAddress($address);
        
        $reflection = new \ReflectionProperty($widget, 'address');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AddressAddressFinder::find
     * если пуст AddressAddressFinder::address
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: address
     */
    public function testFindEmptySeocode()
    {
        $finder = new AddressAddressFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод AddressAddressFinder::find
     */
    public function testFind()
    {
        $finder = new AddressAddressFinder();
        
        $reflection = new \ReflectionProperty($finder, 'address');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, self::$dbClass->address['address_1']['address']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(AddressModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
