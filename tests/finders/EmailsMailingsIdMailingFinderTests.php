<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\EmailsMailingsIdMailingFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\EmailsMailingsFixture;
use app\models\EmailsMailingsModel;

/**
 * Тестирует класс EmailsMailingsIdMailingFinder
 */
class EmailsMailingsIdMailingFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails_mailings'=>EmailsMailingsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new EmailsMailingsIdMailingFinder();
    }
    
    /**
     * Тестирует свойства EmailsMailingsIdMailingFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmailsMailingsIdMailingFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_mailing'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод EmailsMailingsIdMailingFinder::setId_mailing
     */
    public function testSetId_mailing()
    {
        $this->finder->setId_mailing(1);
        
        $reflection = new \ReflectionProperty($this->finder, 'id_mailing');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод EmailsMailingsIdMailingFinder::find
     * если пуст EmailsMailingsIdMailingFinder::id_mailing
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id_mailing
     */
    public function testFindEmptyId_mailing()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод EmailsMailingsIdMailingFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id_mailing');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, self::$dbClass->emails_mailings['email_mailing_1']['id_mailing']);
        
        $result = $this->finder->find();
        
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
