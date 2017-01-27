<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\MailingsIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;
use app\models\MailingsModel;

/**
 * Тестирует класс MailingsIdFinder
 */
class MailingsIdFinderTests extends TestCase
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
     * Тестирует свойства MailingsIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingsIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод MailingsIdFinder::setId
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetIdError()
    {
        $id = null;
        
        $widget = new MailingsIdFinder();
        $widget->setId($id);
    }
    
    /**
     * Тестирует метод MailingsIdFinder::setId
     */
    public function testSetId()
    {
        $id = 2;
        
        $widget = new MailingsIdFinder();
        $widget->setId([$id]);
        
        $reflection = new \ReflectionProperty($widget, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод MailingsIdFinder::find
     * если пуст MailingsIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptySeocode()
    {
        $finder = new MailingsIdFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод MailingsIdFinder::find
     */
    public function testFind()
    {
        $finder = new MailingsIdFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, [self::$dbClass->mailings['mailing_1']['id'], self::$dbClass->mailings['mailing_2']['id']]);
        
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
