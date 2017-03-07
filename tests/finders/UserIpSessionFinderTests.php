<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\UserIpSessionFinder;
use app\filters\AdminProductsFiltersInterface;
use app\models\UserIpModel;

/**
 * Тестирует класс UserIpSessionFinder
 */
class UserIpSessionFinderTests extends TestCase
{
    private $finder;
    
    public function setUp()
    {
        $this->finder = new UserIpSessionFinder();
    }
    
    /**
     * Тестирует свойства UserIpSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserIpSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод UserIpSessionFinder::setKey
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
     * Тестирует метод UserIpSessionFinder::find
     * если пуст UserIpSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод UserIpSessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('key_test', ['ip'=>'127.0.0.0']);
        
        $reflection = new \ReflectionProperty($this->finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 'key_test');
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(UserIpModel::class, $result);
        
        $session->remove('key_test');
        $session->close();
    }
}
