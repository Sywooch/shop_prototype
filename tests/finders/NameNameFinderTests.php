<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\NameNameFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\NamesFixture;
use app\models\NamesModel;

/**
 * Тестирует класс NameNameFinder
 */
class NameNameFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'names'=>NamesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства NameNameFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(NameNameFinder::class);
        
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод NameNameFinder::find
     * если пуст NameNameFinder::name
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: name
     */
    public function testFindEmptySeocode()
    {
        $finder = new NameNameFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод NameNameFinder::find
     */
    public function testFind()
    {
        $finder = new NameNameFinder();
        
        $reflection = new \ReflectionProperty($finder, 'name');
        $reflection->setValue($finder, self::$dbClass->names['name_1']['name']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(NamesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
