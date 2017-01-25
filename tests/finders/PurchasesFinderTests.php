<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\PurchasesFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;
use app\models\PurchasesModel;
use app\collections\PurchasesCollectionInterface;

/**
 * Тестирует класс PurchasesFinder
 */
class PurchasesFinderTests extends TestCase
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
     * Тестирует свойства PurchasesFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchasesFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод PurchasesFinder::find
     */
    public function testFind()
    {
        $finder = new PurchasesFinder();
        $result = $finder->find();
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(PurchasesModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
