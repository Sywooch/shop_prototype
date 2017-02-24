<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\UsersFiltersSessionFinder;
use app\filters\UsersFiltersInterface;

/**
 * Тестирует класс UsersFiltersSessionFinder
 */
class AdminUsersFiltersSessionFinderTests extends TestCase
{
    private $finder;
    
    public function setUp()
    {
        $this->finder = new UsersFiltersSessionFinder();
    }
    
    /**
     * Тестирует свойства UsersFiltersSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UsersFiltersSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод UsersFiltersSessionFinder::setKey
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
     * Тестирует метод UsersFiltersSessionFinder::find
     * если пуст UsersFiltersSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод UsersFiltersSessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('key_test', ['sortingType'=>SORT_ASC, 'sortingField'=>'orders']);
        
        $reflection = new \ReflectionProperty($this->finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 'key_test');
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(UsersFiltersInterface::class, $result);
        
        $session->remove('key_test');
        $session->close();
    }
}
