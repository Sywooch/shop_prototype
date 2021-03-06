<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\EmailEmailFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\EmailsFixture;
use app\models\EmailsModel;

/**
 * Тестирует класс EmailEmailFinder
 */
class EmailEmailFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства EmailEmailFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmailEmailFinder::class);
        
        $this->assertTrue($reflection->hasProperty('email'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод EmailEmailFinder::setEmail
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetEmailError()
    {
        $email = null;
        
        $widget = new EmailEmailFinder();
        $widget->setEmail($email);
    }
    
    /**
     * Тестирует метод EmailEmailFinder::setEmail
     */
    public function testSetEmail()
    {
        $email = 'email';
        
        $widget = new EmailEmailFinder();
        $widget->setEmail($email);
        
        $reflection = new \ReflectionProperty($widget, 'email');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод EmailEmailFinder::find
     * если пуст EmailEmailFinder::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testFindEmptySeocode()
    {
        $finder = new EmailEmailFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод EmailEmailFinder::find
     */
    public function testFind()
    {
        $fixture = self::$dbClass->emails['email_1'];
        
        $finder = new EmailEmailFinder();
        
        $reflection = new \ReflectionProperty($finder, 'email');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $fixture['email']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(EmailsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
