<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\PhonePhoneFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PhonesFixture;
use app\models\PhonesModel;

/**
 * Тестирует класс PhonePhoneFinder
 */
class PhonePhoneFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'phones'=>PhonesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PhonePhoneFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PhonePhoneFinder::class);
        
        $this->assertTrue($reflection->hasProperty('phone'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод PhonePhoneFinder::find
     * если пуст PhonePhoneFinder::phone
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: phone
     */
    public function testFindEmptySeocode()
    {
        $finder = new PhonePhoneFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод PhonePhoneFinder::find
     */
    public function testFind()
    {
        $finder = new PhonePhoneFinder();
        
        $reflection = new \ReflectionProperty($finder, 'phone');
        $reflection->setValue($finder, self::$dbClass->phones['phone_1']['phone']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(PhonesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
