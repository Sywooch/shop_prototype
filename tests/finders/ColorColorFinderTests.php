<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ColorColorFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\ColorsFixture;
use app\models\ColorsModel;

/**
 * Тестирует класс ColorColorFinder
 */
class ColorColorFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new ColorColorFinder();
    }
    
    /**
     * Тестирует свойства ColorColorFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ColorColorFinder::class);
        
        $this->assertTrue($reflection->hasProperty('color'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ColorColorFinder::setColor
     */
    public function testSetColor()
    {
        $this->finder->setColor('color');
        
        $reflection = new \ReflectionProperty($this->finder, 'color');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод ColorColorFinder::find
     * если пуст ColorColorFinder::color
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: color
     */
    public function testFindEmptyColor()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод ColorColorFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'color');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, self::$dbClass->colors['color_1']['color']);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(ColorsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
