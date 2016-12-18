<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SizesFilterSphinxFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{SizesFixture,
    ProductsSizesFixture,
    ProductsFixture};
use app\models\SizesModel;

/**
 * Тестирует класс SizesFilterSphinxFinder
 */
class SizesFilterSphinxFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'sizes'=>SizesFixture::class,
                'products'=>ProductsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства SizesFilterSphinxFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SizesFilterSphinxFinder::class);
        
        $this->assertTrue($reflection->hasProperty('sphinx'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SizesFilterSphinxFinder::setSphinx
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSphinxError()
    {
        $sphinx = new class() {};
        
        $finder = new SizesFilterSphinxFinder();
        
        $finder->setSphinx($sphinx);
    }
    
    /**
     * Тестирует метод SizesFilterSphinxFinder::setSphinx
     */
    public function testSetSphinx()
    {
        $sphinx = [1, 12, 34];
        
        $finder = new SizesFilterSphinxFinder();
        
        $finder->setSphinx($sphinx);
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод SizesFilterSphinxFinder::run
     * если пуст SizesFilterSphinxFinder::sphinx
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: sphinx
     */
    public function testRunEmptySphinx()
    {
        $finder = new SizesFilterSphinxFinder();
        $result = $finder->find();
    }
    
    /**
     * Тестирует метод SizesFilterSphinxFinder::run
     */
    public function testRun()
    {
        $sphinx = [1, 2, 3, 4, 5];
        $finder = new SizesFilterSphinxFinder();
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $sphinx);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(SizesModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
