<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CurrencyIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\models\CurrencyModel;

/**
 * Тестирует класс CurrencyIdFinder
 */
class CurrencyIdFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CurrencyIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencyIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CurrencyIdFinder::setId
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetIdError()
    {
        $id = null;
        
        $widget = new CurrencyIdFinder();
        $widget->setId($id);
    }
    
    /**
     * Тестирует метод CurrencyIdFinder::setId
     */
    public function testSetId()
    {
        $id = 2;
        
        $widget = new CurrencyIdFinder();
        $widget->setId($id);
        
        $reflection = new \ReflectionProperty($widget, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод CurrencyIdFinder::find
     * если пуст CurrencyIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptyId()
    {
        $finder = new CurrencyIdFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод CurrencyIdFinder::find
     */
    public function testFind()
    {
        $finder = new CurrencyIdFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 1);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
