<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\Collection;

/**
 * Тестирует класс app\models\Collection
 */
class CollectionTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'cities'=>'app\tests\sources\fixtures\CitiesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\Collection');
    }
    
    /**
     * Тестирует метод Collection::add
     */
    public function testAdd()
    {
        $collection = new Collection();
        
        $this->assertTrue($collection->isEmpty());
        
        $collection->add(new class() {});
        
        $this->assertFalse($collection->isEmpty());
    }
    
    /**
     * Тестирует метод Collection::iteration
     */
    public function testIteration()
    {
        $object = new class() {};
        
        $collection = new Collection();
        $collection->add($object);
        $collection->add($object);
        $collection->add($object);
        $collection->add($object);
        
        $counter = 0;
        foreach ($collection as $element) {
            ++$counter;
        }
        
        $this->assertEquals(4, $counter);
    }
    
    /**
     * Тестирует метод Collection::get
     */
    public function testGet()
    {
        $object = new class() {
            public $data = 3;
        };
        
        $collection = new Collection();
        $collection->add($object);
        $collection->add($object);
        $collection->add($object);
        
        $this->assertEquals(9, $collection->data);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
