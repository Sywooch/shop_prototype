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
     * Тестирует метод EmailEmailFinder::find
     * если пуст EmailEmailFinder::email
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: email
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
        $reflection->setValue($finder, $fixture['email']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(EmailsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
