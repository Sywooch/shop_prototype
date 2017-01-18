<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\EmailsMailingsEmailFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture};
use app\models\EmailsMailingsModel;

/**
 * Тестирует класс EmailsMailingsEmailFinder
 */
class EmailsMailingsEmailFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails_mailings'=>EmailsMailingsFixture::class,
                'emails'=>EmailsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства EmailsMailingsEmailFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmailsMailingsEmailFinder::class);
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод EmailsMailingsEmailFinder::find
     * если пуст EmailsMailingsEmailFinder::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testFindEmptySeocode()
    {
        $finder = new EmailsMailingsEmailFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод EmailsMailingsEmailFinder::find
     */
    public function testFind()
    {
        $finder = new EmailsMailingsEmailFinder();
        
        $reflection = new \ReflectionProperty($finder, 'email');
        $reflection->setValue($finder, self::$dbClass->emails['email_1']['email']);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(EmailsMailingsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
