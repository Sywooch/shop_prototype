<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CurrencyExcludeIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\models\CurrencyModel;

/**
 * Тестирует класс CurrencyExcludeIdFinder
 */
class CurrencyExcludeIdFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new CurrencyExcludeIdFinder();
    }
    
    /**
     * Тестирует свойства CurrencyExcludeIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencyExcludeIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CurrencyExcludeIdFinder::setId
     */
    public function testSetId()
    {
        $this->finder->setId(1);
        
        $reflection = new \ReflectionProperty($this->finder, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertSame(1, $result);
    }
    
    /**
     * Тестирует метод CurrencyExcludeIdFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 1);
        
        $currency = $this->finder->find();
        
        $this->assertInternalType('array', $currency);
        $this->assertNotEmpty($currency);
        foreach($currency as $currency) {
            $this->assertInstanceOf(CurrencyModel::class, $currency);
            $this->assertEquals(0, $currency->main);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
