<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\MailingNameFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;
use app\models\MailingsModel;

/**
 * Тестирует класс MailingNameFinder
 */
class MailingNameFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>MailingsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new MailingNameFinder();
    }
    
    /**
     * Тестирует свойства MailingNameFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingNameFinder::class);
        
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод MailingNameFinder::setName
     */
    public function testSetName()
    {
        $this->finder->setName('Name');
        
        $reflection = new \ReflectionProperty($this->finder, 'name');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод MailingNameFinder::find
     * если пуст MailingNameFinder::name
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: name
     */
    public function testFindEmptyName()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод MailingNameFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'name');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, self::$dbClass->mailings['mailing_1']['name']);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(MailingsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
