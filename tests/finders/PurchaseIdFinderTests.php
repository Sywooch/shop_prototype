<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\PurchaseIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;
use app\models\PurchasesModel;

/**
 * Тестирует класс PurchaseIdFinder
 */
class PurchaseIdFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PurchaseIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchaseIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод PurchaseIdFinder::setId
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetIdError()
    {
        $id = null;
        
        $widget = new PurchaseIdFinder();
        $widget->setId($id);
    }
    
    /**
     * Тестирует метод PurchaseIdFinder::setId
     */
    public function testSetId()
    {
        $id = 2;
        
        $widget = new PurchaseIdFinder();
        $widget->setId($id);
        
        $reflection = new \ReflectionProperty($widget, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод PurchaseIdFinder::find
     * если пуст PurchaseIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptyId()
    {
        $finder = new PurchaseIdFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод PurchaseIdFinder::find
     */
    public function testFind()
    {
        $finder = new PurchaseIdFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, self::$dbClass->purchases['purchase_1']['id']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(PurchasesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
