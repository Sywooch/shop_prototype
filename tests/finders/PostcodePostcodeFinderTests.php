<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\PostcodePostcodeFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PostcodesFixture;
use app\models\PostcodesModel;

/**
 * Тестирует класс PostcodePostcodeFinder
 */
class PostcodePostcodeFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'postcodes'=>PostcodesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PostcodePostcodeFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PostcodePostcodeFinder::class);
        
        $this->assertTrue($reflection->hasProperty('postcode'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод PostcodePostcodeFinder::setPostcode
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPostcodeError()
    {
        $postcode = null;
        
        $widget = new PostcodePostcodeFinder();
        $widget->setPostcode($postcode);
    }
    
    /**
     * Тестирует метод PostcodePostcodeFinder::setPostcode
     */
    public function testSetPostcode()
    {
        $postcode = 'postcode';
        
        $widget = new PostcodePostcodeFinder();
        $widget->setPostcode($postcode);
        
        $reflection = new \ReflectionProperty($widget, 'postcode');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод PostcodePostcodeFinder::find
     * если пуст PostcodePostcodeFinder::postcode
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: postcode
     */
    public function testFindEmptySeocode()
    {
        $finder = new PostcodePostcodeFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод PostcodePostcodeFinder::find
     */
    public function testFind()
    {
        $finder = new PostcodePostcodeFinder();
        
        $reflection = new \ReflectionProperty($finder, 'postcode');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, self::$dbClass->postcodes['postcode_1']['postcode']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(PostcodesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
