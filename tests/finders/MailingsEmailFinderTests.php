<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\MailingsEmailFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture,
    MailingsFixture};
use app\models\MailingsModel;

/**
 * Тестирует класс MailingsEmailFinder
 */
class MailingsEmailFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::class,
                'mailings'=>MailingsFixture::class,
                'emails_mailings'=>EmailsMailingsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства MailingsEmailFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingsEmailFinder::class);
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод MailingsEmailFinder::setEmail
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetEmailError()
    {
        $email = null;
        
        $widget = new MailingsEmailFinder();
        $widget->setEmail($email);
    }
    
    /**
     * Тестирует метод MailingsEmailFinder::setEmail
     */
    public function testSetEmail()
    {
        $email = 'email';
        
        $widget = new MailingsEmailFinder();
        $widget->setEmail($email);
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод MailingsEmailFinder::find
     * если пуст MailingsEmailFinder::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testFindEmptyEmail()
    {
        $finder = new MailingsEmailFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод MailingsEmailFinder::find
     */
    public function testFind()
    {
        $finder = new MailingsEmailFinder();
        
        $reflection = new \ReflectionProperty($finder, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, self::$dbClass->emails['email_1']['email']);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(MailingsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
