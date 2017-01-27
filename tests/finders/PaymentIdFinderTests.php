<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\PaymentIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;
use app\models\PaymentsModel;

/**
 * Тестирует класс PaymentIdFinder
 */
class PaymentIdFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'payments'=>PaymentsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PaymentIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PaymentIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод PaymentIdFinder::setId
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetIdError()
    {
        $id = null;
        
        $widget = new PaymentIdFinder();
        $widget->setId($id);
    }
    
    /**
     * Тестирует метод PaymentIdFinder::setId
     */
    public function testSetId()
    {
        $id = 2;
        
        $widget = new PaymentIdFinder();
        $widget->setId($id);
        
        $reflection = new \ReflectionProperty($widget, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод PaymentIdFinder::find
     * если пуст PaymentIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptyId()
    {
        $finder = new PaymentIdFinder();
        $payments = $finder->find();
    }
    
    /**
     * Тестирует метод PaymentIdFinder::find
     */
    public function testFind()
    {
        $finder = new PaymentIdFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 1);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(PaymentsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
