<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use yii\db\ActiveQuery;
use app\finders\AdminUsersCsvFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\filters\{UsersFiltersInterface,
    UsersFilters};

/**
 * Тестирует класс AdminUsersCsvFinder
 */
class AdminUsersCsvFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new AdminUsersCsvFinder();
    }
    
    /**
     * Тестирует свойства AdminUsersCsvFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminUsersCsvFinder::class);
        
        $this->assertTrue($reflection->hasProperty('filters'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод AdminUsersCsvFinder::setFilters
     */
    public function testSetFilters()
    {
        $filters = new class() extends UsersFilters {};
        
        $this->finder->setFilters($filters);
        
        $reflection = new \ReflectionProperty($this->finder, 'filters');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInstanceOf(UsersFiltersInterface::class, $result);
    }
    
    /**
     * Тестирует метод AdminUsersCsvFinder::find
     * если пуст AdminUsersCsvFinder::filters
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: filters
     */
    public function testFindEmptyFilters()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод AdminUsersCsvFinder::find
     * если фильтры пусты
     */
    public function testFindEmptyPageFilters()
    {
        $filters = new class() extends UsersFilters {};
        
        $reflection = new \ReflectionProperty($this->finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, $filters);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(ActiveQuery::class, $result);
    }
    
    /**
     * Тестирует метод AdminUsersCsvFinder::find
     * если фильтры не пусты
     */
    public function testFindEmptyPage()
    {
        $filters = new class() extends UsersFilters {
            public $sortingField = 'name';
        };
        
        $reflection = new \ReflectionProperty($this->finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, $filters);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(ActiveQuery::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
