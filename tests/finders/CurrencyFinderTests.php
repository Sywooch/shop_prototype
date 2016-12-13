<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CurrencyFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\models\CurrencyModel;

/**
 * Тестирует класс CurrencyFinder
 */
class CurrencyFinderTests extends TestCase
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
     * Тестирует свойства CurrencyFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencyFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CurrencyFinder::find
     */
    public function testFind()
    {
        $finder = new CurrencyFinder();
        $currency = $finder->find();
        
        $this->assertInternalType('array', $currency);
        $this->assertNotEmpty($currency);
        foreach($currency as $category) {
            $this->assertInstanceOf(CurrencyModel::class, $category);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
