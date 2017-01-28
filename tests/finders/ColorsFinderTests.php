<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ColorsFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\ColorsFixture;
use app\models\ColorsModel;

/**
 * Тестирует класс ColorsFinder
 */
class ColorsFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ColorsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ColorsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ColorsFinder::find
     */
    public function testFind()
    {
        $finder = new ColorsFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ColorsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
