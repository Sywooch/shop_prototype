<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ColorsFilterSphinxFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ColorsFixture,
    ProductsColorsFixture,
    ProductsFixture};
use app\models\ColorsModel;

/**
 * Тестирует класс ColorsFilterSphinxFinder
 */
class ColorsFilterSphinxFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::class,
                'products'=>ProductsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ColorsFilterSphinxFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ColorsFilterSphinxFinder::class);
        
        $this->assertTrue($reflection->hasProperty('sphinx'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ColorsFilterSphinxFinder::setSphinx
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSphinxError()
    {
        $sphinx = new class() {};
        
        $finder = new ColorsFilterSphinxFinder();
        
        $finder->setSphinx($sphinx);
    }
    
    /**
     * Тестирует метод ColorsFilterSphinxFinder::setSphinx
     */
    public function testSetSphinx()
    {
        $sphinx = [1, 12, 34];
        
        $finder = new ColorsFilterSphinxFinder();
        
        $finder->setSphinx($sphinx);
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод ColorsFilterSphinxFinder::run
     * если пуст ColorsFilterSphinxFinder::sphinx
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: sphinx
     */
    public function testRunEmptySphinx()
    {
        $finder = new ColorsFilterSphinxFinder();
        $result = $finder->find();
    }
    
    /**
     * Тестирует метод ColorsFilterSphinxFinder::run
     */
    public function testRun()
    {
        $sphinx = [1, 2, 3, 4, 5];
        $finder = new ColorsFilterSphinxFinder();
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $sphinx);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ColorsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
