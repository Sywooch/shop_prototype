<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\BrandsFilterSphinxFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{BrandsFixture,
    ProductsFixture};
use app\models\BrandsModel;

/**
 * Тестирует класс BrandsFilterSphinxFinder
 */
class BrandsFilterSphinxFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'brands'=>BrandsFixture::class,
                'products'=>ProductsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства BrandsFilterSphinxFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(BrandsFilterSphinxFinder::class);
        
        $this->assertTrue($reflection->hasProperty('sphinx'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод BrandsFilterSphinxFinder::setSphinx
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSphinxError()
    {
        $sphinx = new class() {};
        
        $finder = new BrandsFilterSphinxFinder();
        
        $finder->setSphinx($sphinx);
    }
    
    /**
     * Тестирует метод BrandsFilterSphinxFinder::setSphinx
     */
    public function testSetSphinx()
    {
        $sphinx = [1, 12, 34];
        
        $finder = new BrandsFilterSphinxFinder();
        
        $finder->setSphinx($sphinx);
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод BrandsFilterSphinxFinder::run
     * если пуст BrandsFilterSphinxFinder::sphinx
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: sphinx
     */
    public function testRunEmptySphinx()
    {
        $finder = new BrandsFilterSphinxFinder();
        $result = $finder->find();
    }
    
    /**
     * Тестирует метод BrandsFilterSphinxFinder::run
     */
    public function testRun()
    {
        $sphinx = [1, 2, 3, 4, 5];
        $finder = new BrandsFilterSphinxFinder();
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $sphinx);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(BrandsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
