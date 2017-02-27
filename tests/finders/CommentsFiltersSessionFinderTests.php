<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CommentsFiltersSessionFinder;
use app\filters\CommentsFiltersInterface;

/**
 * Тестирует класс CommentsFiltersSessionFinder
 */
class AdminCommentsFiltersSessionFinderTests extends TestCase
{
    private $finder;
    
    public function setUp()
    {
        $this->finder = new CommentsFiltersSessionFinder();
    }
    
    /**
     * Тестирует свойства CommentsFiltersSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentsFiltersSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CommentsFiltersSessionFinder::setKey
     */
    public function testSetKey()
    {
        $this->finder->setKey('key');
        
        $reflection = new \ReflectionProperty($this->finder, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CommentsFiltersSessionFinder::find
     * если пуст CommentsFiltersSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод CommentsFiltersSessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('key_test', ['sortingType'=>SORT_ASC, 'sortingField'=>'date']);
        
        $reflection = new \ReflectionProperty($this->finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 'key_test');
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(CommentsFiltersInterface::class, $result);
        
        $session->remove('key_test');
        $session->close();
    }
}
