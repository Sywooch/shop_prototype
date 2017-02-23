<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\UsersFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{PurchasesFixture,
    UsersFixture};
use app\models\UsersModel;
use app\collections\UsersCollection;

/**
 * Тестирует класс UsersFinder
 */
class UsersFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
                'purchases'=>PurchasesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new UsersFinder();
    }
    
    /**
     * Тестирует свойства UsersFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UsersFinder::class);
        
        $this->assertTrue($reflection->hasProperty('page'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод UsersFinder::setPage
     */
    public function testSetPage()
    {
        $this->finder->setPage(2);
        
        $reflection = new \ReflectionProperty($this->finder, 'page');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод UsersFinder::find
     * если page === null
     */
    public function testFind()
    {
        $result = $this->finder->find();
        
        $this->assertInstanceOf(UsersCollection::class, $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(UsersModel::class, $item);
        }
    }
    
    /**
     * Тестирует метод UsersFinder::find
     * если page === true
     */
    public function testFindPage()
    {
        $reflection = new \ReflectionProperty($this->finder, 'page');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 2);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(UsersCollection::class, $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(UsersModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
