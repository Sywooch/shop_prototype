<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\UserEmailFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};
use app\models\UsersModel;

/**
 * Тестирует класс UserEmailFinder
 */
class UserEmailFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
                'emails'=>EmailsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства UserEmailFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserEmailFinder::class);
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод UserEmailFinder::find
     * если пуст UserEmailFinder::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testFindEmptyId()
    {
        $finder = new UserEmailFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод UserEmailFinder::find
     */
    public function testFind()
    {
        $email = self::$dbClass->emails['email_1']['email'];
        
        $finder = new UserEmailFinder();
        
        $reflection = new \ReflectionProperty($finder, 'email');
        $reflection->setValue($finder, $email);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(UsersModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
