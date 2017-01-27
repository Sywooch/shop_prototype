<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\MailingsNotEmailFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    EmailsMailingsFixture,
    MailingsFixture};
use app\models\MailingsModel;

/**
 * Тестирует класс MailingsNotEmailFinder
 */
class MailingsNotEmailFinderTests extends TestCase
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
     * Тестирует свойства MailingsNotEmailFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingsNotEmailFinder::class);
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод MailingsNotEmailFinder::setEmail
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetEmailError()
    {
        $email = null;
        
        $widget = new MailingsNotEmailFinder();
        $widget->setEmail($email);
    }
    
    /**
     * Тестирует метод MailingsNotEmailFinder::setEmail
     */
    public function testSetEmail()
    {
        $email = 'email';
        
        $widget = new MailingsNotEmailFinder();
        $widget->setEmail($email);
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод MailingsNotEmailFinder::find
     * если пуст MailingsNotEmailFinder::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testFindEmptyEmail()
    {
        $finder = new MailingsNotEmailFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод MailingsNotEmailFinder::find
     */
    public function testFind()
    {
        $finder = new MailingsNotEmailFinder();
        
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
