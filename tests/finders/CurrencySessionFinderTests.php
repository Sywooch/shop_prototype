<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CurrencySessionFinder;
use app\models\CurrencyModel;

/**
 * Тестирует класс CurrencySessionFinder
 */
class CurrencySessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства CurrencySessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CurrencySessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CurrencySessionFinder::setKey
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetKeyError()
    {
        $key = null;
        
        $widget = new CurrencySessionFinder();
        $widget->setKey($key);
    }
    
    /**
     * Тестирует метод CurrencySessionFinder::setKey
     */
    public function testSetKey()
    {
        $key = 'key';
        
        $widget = new CurrencySessionFinder();
        $widget->setKey($key);
        
        $reflection = new \ReflectionProperty($widget, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CurrencySessionFinder::find
     * если пуст CurrencySessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $finder = new CurrencySessionFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод CurrencySessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('key_test', ['id'=>1, 'code'=>'MONEY', 'exchange_rate'=>12.8]);
        
        $finder = new CurrencySessionFinder();
        
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'key_test');
        
        $result = $finder->find();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
        $this->assertNotEmpty($result->toArray());
        $this->assertSame(['id'=>1, 'code'=>'MONEY', 'exchange_rate'=>12.8], $result->toArray());
        
        $session->remove('key_test');
        $session->close();
    }
}
