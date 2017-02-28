<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CommentIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CommentsFixture;
use app\models\CommentsModel;

/**
 * Тестирует класс CommentIdFinder
 */
class CommentIdFinderTests extends TestCase
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
        $this->finder = new CommentIdFinder();
    }
    
    /**
     * Тестирует свойства CommentIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CommentIdFinder::setId
     */
    public function testSetId()
    {
        $this->finder->setId(45);
        
        $reflection = new \ReflectionProperty($this->finder, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод CommentIdFinder::find
     * если пуст CommentIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptyProduct()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод CommentIdFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 1);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(CommentsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
