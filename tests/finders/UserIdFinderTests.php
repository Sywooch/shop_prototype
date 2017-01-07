<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\UserIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\models\UsersModel;

/**
 * Тестирует класс UserIdFinder
 */
class UserIdFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства UserIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод UserIdFinder::find
     * если пуст UserIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptyId()
    {
        $finder = new UserIdFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод UserIdFinder::find
     */
    public function testFind()
    {
        $finder = new UserIdFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id');
        $reflection->setValue($finder, 1);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(UsersModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
