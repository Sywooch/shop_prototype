<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\MailingsFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;
use app\models\MailingsModel;

/**
 * Тестирует класс MailingsFinder
 */
class MailingsFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>MailingsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства MailingsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод MailingsFinder::find
     */
    public function testFind()
    {
        $finder = new MailingsFinder();
        $mailings = $finder->find();
        
        $this->assertInternalType('array', $mailings);
        $this->assertNotEmpty($mailings);
        foreach($mailings as $mailing) {
            $this->assertInstanceOf(MailingsModel::class, $mailing);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
