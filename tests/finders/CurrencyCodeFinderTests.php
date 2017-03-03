<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CurrencyCodeFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\models\CurrencyModel;

/**
 * Тестирует класс CurrencyCodeFinder
 */
class CurrencyCodeFinderTests extends TestCase
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
        $this->finder = new CurrencyCodeFinder();
    }
    
    /**
     * Тестирует свойства CurrencyCodeFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencyCodeFinder::class);
        
        $this->assertTrue($reflection->hasProperty('code'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CurrencyCodeFinder::setCode
     */
    public function testSetCode()
    {
        $this->finder->setCode('COD');
        
        $reflection = new \ReflectionProperty($this->finder, 'code');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CurrencyCodeFinder::find
     * если пуст CurrencyCodeFinder::code
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: code
     */
    public function testFindEmptyCode()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод CurrencyCodeFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'code');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, self::$dbClass->currency['currency_1']['code']);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
