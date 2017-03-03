<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CurrencySessionDBMSFinder;
use app\models\CurrencyModel;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;

/**
 * Тестирует класс CurrencySessionDBMSFinder
 */
class CurrencySessionDBMSFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CurrencySessionDBMSFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencySessionDBMSFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    public function setUp()
    {
        $this->finder = new CurrencySessionDBMSFinder();
    }
    
    /**
     * Тестирует метод CurrencySessionDBMSFinder::setKey
     */
    public function testSetKey()
    {
        $key = 'key';
        
        $this->finder->setKey($key);
        
        $reflection = new \ReflectionProperty($this->finder, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CurrencySessionDBMSFinder::find
     * если пуст CurrencySessionDBMSFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод CurrencySessionDBMSFinder::find
     * ключ в сессии
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('key_test', ['id'=>1]);
        
        $reflection = new \ReflectionProperty($this->finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 'key_test');
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
        
        $session->remove('key_test');
        $session->close();
    }
    
    /**
     * Тестирует метод CurrencySessionDBMSFinder::find
     * ключа в сессии нет
     */
    public function testFindEmptySession()
    {
        $reflection = new \ReflectionProperty($this->finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 'key_test');
        
        $result = $this->finder->find();
        
        $this->assertNull($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
