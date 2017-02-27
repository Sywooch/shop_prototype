<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CommentsFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CommentsFixture;
use app\models\CommentsModel;
use app\collections\CommentsCollection;
use app\filters\{CommentsFilters,
    CommentsFiltersInterface};

/**
 * Тестирует класс CommentsFinder
 */
class CommentsFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'comments'=>CommentsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new CommentsFinder();
    }
    
    /**
     * Тестирует свойства CommentsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('page'));
        $this->assertTrue($reflection->hasProperty('filters'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CommentsFinder::setPage
     */
    public function testSetPage()
    {
        $this->finder->setPage(2);
        
        $reflection = new \ReflectionProperty($this->finder, 'page');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод CommentsFinder::setFilters
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
     * Тестирует метод CommentsFinder::find
     * page === null
     * filters === null
     */
    public function testFind()
    {
        $filters = new class() extends CommentsFilters {};
        
        $reflection = new \ReflectionProperty($this->finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, $filters);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(CommentsCollection::class, $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(CommentsModel::class, $item);
        }
    }
    
    /**
     * Тестирует метод CommentsFinder::find
     * page === true
     * filters === null
     */
    public function testFindPage()
    {
        $filters = new class() extends CommentsFilters {};
        
        $reflection = new \ReflectionProperty($this->finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, $filters);
        
        $reflection = new \ReflectionProperty($this->finder, 'page');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 2);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(CommentsCollection::class, $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(CommentsModel::class, $item);
        }
    }
    
    /**
     * Тестирует метод CommentsFinder::find
     * page === null
     * filters === true
     */
    public function testFindFilters()
    {
        $filters = new class() extends CommentsFilters {
            public $sortingField = 'date';
            public $sortingType = SORT_ASC;
        };
        
        $reflection = new \ReflectionProperty($this->finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, $filters);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(CommentsCollection::class, $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(CommentsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
