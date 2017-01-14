<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SurnameSurnameFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\SurnamesFixture;
use app\models\SurnamesModel;

/**
 * Тестирует класс SurnameSurnameFinder
 */
class SurnameSurnameFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'surnames'=>SurnamesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства SurnameSurnameFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SurnameSurnameFinder::class);
        
        $this->assertTrue($reflection->hasProperty('surname'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SurnameSurnameFinder::find
     * если пуст SurnameSurnameFinder::surname
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: surname
     */
    public function testFindEmptySeocode()
    {
        $finder = new SurnameSurnameFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод SurnameSurnameFinder::find
     */
    public function testFind()
    {
        $finder = new SurnameSurnameFinder();
        
        $reflection = new \ReflectionProperty($finder, 'surname');
        $reflection->setValue($finder, self::$dbClass->surnames['surname_1']['surname']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(SurnamesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
