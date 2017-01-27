<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CityCityFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CitiesFixture;
use app\models\CitiesModel;

/**
 * Тестирует класс CityCityFinder
 */
class CityCityFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'cities'=>CitiesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CityCityFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CityCityFinder::class);
        
        $this->assertTrue($reflection->hasProperty('city'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CityCityFinder::setСity
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetСityError()
    {
        $city = null;
        
        $widget = new CityCityFinder();
        $widget->setСity($city);
    }
    
    /**
     * Тестирует метод CityCityFinder::setСity
     */
    public function testSetСity()
    {
        $city = 'city';
        
        $widget = new CityCityFinder();
        $widget->setСity($city);
        
        $reflection = new \ReflectionProperty($widget, 'city');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CityCityFinder::find
     * если пуст CityCityFinder::city
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: city
     */
    public function testFindEmptySeocode()
    {
        $finder = new CityCityFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод CityCityFinder::find
     */
    public function testFind()
    {
        $finder = new CityCityFinder();
        
        $reflection = new \ReflectionProperty($finder, 'city');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, self::$dbClass->cities['city_1']['city']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(CitiesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
