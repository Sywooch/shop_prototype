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
     * Тестирует метод SurnameSurnameFinder::setSurname
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSurnameError()
    {
        $surname = null;
        
        $widget = new SurnameSurnameFinder();
        $widget->setSurname($surname);
    }
    
    /**
     * Тестирует метод SurnameSurnameFinder::setSurname
     */
    public function testSetSurname()
    {
        $surname = 'surname';
        
        $widget = new SurnameSurnameFinder();
        $widget->setSurname($surname);
        
        $reflection = new \ReflectionProperty($widget, 'surname');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
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
        $reflection->setAccessible(true);
        $reflection->setValue($finder, self::$dbClass->surnames['surname_1']['surname']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(SurnamesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
