<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\MailingIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;
use app\models\MailingsModel;

/**
 * Тестирует класс MailingIdFinder
 */
class MailingIdFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>MailingsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new MailingIdFinder();
    }
    
    /**
     * Тестирует свойства MailingIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод MailingIdFinder::setId
     */
    public function testSetId()
    {
        $this->finder->setId(2);
        
        $reflection = new \ReflectionProperty($this->finder, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод MailingIdFinder::find
     * если пуст MailingIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptySeocode()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод MailingIdFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 1);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(MailingsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
