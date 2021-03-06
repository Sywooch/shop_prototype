<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CityGetSaveCityService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CitiesFixture;
use app\models\CitiesModel;

/**
 * Тестирует класс CityGetSaveCityService
 */
class CityGetSaveCityServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'cities'=>CitiesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CityGetSaveCityService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CityGetSaveCityService::class);
        
        $this->assertTrue($reflection->hasProperty('citiesModel'));
        $this->assertTrue($reflection->hasProperty('city'));
    }
    
    /**
     * Тестирует метод CityGetSaveCityService::getCity
     */
    public function testGetCity()
    {
        $service = new CityGetSaveCityService();
        
        $reflection = new \ReflectionProperty($service, 'city');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$dbClass->cities['city_1']['city']);
        
        $reflection = new \ReflectionMethod($service, 'getCity');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInstanceOf(CitiesModel::class, $result);
    }
    
    /**
     * Тестирует метод CityGetSaveCityService::setCity
     * передаю неверный тип параметра
     * @expectedException TypeError
     */
    public function testSetCityError()
    {
        $service = new CityGetSaveCityService();
        $service->setCity([]);
    }
    
    /**
     * Тестирует метод CityGetSaveCityService::setCity
     */
    public function testSetCity()
    {
        $service = new CityGetSaveCityService();
        $service->setCity('City');
        
        $reflection = new \ReflectionProperty($service, 'city');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($service);
        
        $this->assertEquals('City', $result);
    }
    
    /**
     * Тестирует метод CityGetSaveCityService::get
     * если пуст CityGetSaveCityService::city
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: city
     */
    public function testGetEmptyCity()
    {
        $service = new CityGetSaveCityService();
        $service->get();
    }
    
    /**
     * Тестирует метод CityGetSaveCityService::get
     * если city уже в СУБД
     */
    public function testGetExistsAddress()
    {
        $service = new CityGetSaveCityService();
        
        $reflection = new \ReflectionProperty($service, 'city');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$dbClass->cities['city_1']['city']);
        
        $result = $service->get();
        
        $this->assertInstanceOf(CitiesModel::class, $result);
    }
    
    /**
     * Тестирует метод CityGetSaveCityService::get
     * если city еще не в СУБД
     */
    public function testGetNotExistsAddress()
    {
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{cities}} WHERE [[city]]=:city')->bindValue(':city', 'Оттава')->queryOne();
        $this->assertEmpty($result);
        
        $service = new CityGetSaveCityService();
        
        $reflection = new \ReflectionProperty($service, 'city');
        $reflection->setAccessible(true);
        $reflection->setValue($service, 'Оттава');
        
        $result = $service->get();
        
        $this->assertInstanceOf(CitiesModel::class, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{cities}} WHERE [[city]]=:city')->bindValue(':city', 'Оттава')->queryOne();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals('Оттава', $result['city']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
