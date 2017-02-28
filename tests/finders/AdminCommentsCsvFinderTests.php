<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use yii\db\ActiveQuery;
use app\finders\AdminCommentsCsvFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CommentsFixture;
use app\filters\{CommentsFiltersInterface,
    CommentsFilters};

/**
 * Тестирует класс AdminCommentsCsvFinder
 */
class AdminCommentsCsvFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'comments'=>CommentsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new AdminCommentsCsvFinder();
    }
    
    /**
     * Тестирует свойства AdminCommentsCsvFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCommentsCsvFinder::class);
        
        $this->assertTrue($reflection->hasProperty('filters'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод AdminCommentsCsvFinder::setFilters
     */
    public function testSetFilters()
    {
        $filters = new class() extends CommentsFilters {};
        
        $this->finder->setFilters($filters);
        
        $reflection = new \ReflectionProperty($this->finder, 'filters');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInstanceOf(CommentsFiltersInterface::class, $result);
    }
    
    /**
     * Тестирует метод AdminCommentsCsvFinder::find
     * если пуст AdminCommentsCsvFinder::filters
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: filters
     */
    public function testFindEmptyFilters()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод AdminCommentsCsvFinder::find
     * если фильтры пусты
     */
    public function testFindEmptyPageFilters()
    {
        $filters = new class() extends CommentsFilters {};
        
        $reflection = new \ReflectionProperty($this->finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, $filters);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(ActiveQuery::class, $result);
    }
    
    /**
     * Тестирует метод AdminCommentsCsvFinder::find
     * если фильтры не пусты
     */
    public function testFindEmptyPage()
    {
        $filters = new class() extends CommentsFilters {
            public $sortingField = 'date';
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
