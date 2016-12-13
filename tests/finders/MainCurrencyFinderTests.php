<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\MainCurrencyFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\models\CurrencyModel;

/**
 * Тестирует класс MainCurrencyFinder
 */
class MainCurrencyFinderTests extends TestCase
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
     * Тестирует свойства MainCurrencyFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MainCurrencyFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод MainCurrencyFinder::find
     */
    public function testFind()
    {
        $finder = new MainCurrencyFinder();
        $result = $finder->find();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
