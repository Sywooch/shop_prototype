<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\PurchasesSessionFinder;
use app\models\PurchasesModel;
use app\collections\PurchasesCollectionInterface;

/**
 * Тестирует класс PurchasesSessionFinder
 */
class PurchasesSessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства PurchasesSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchasesSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод PurchasesSessionFinder::setKey
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetKeyError()
    {
        $key = null;
        
        $widget = new PurchasesSessionFinder();
        $widget->setKey($key);
    }
    
    /**
     * Тестирует метод PurchasesSessionFinder::setKey
     */
    public function testSetKey()
    {
        $key = 'key';
        
        $widget = new PurchasesSessionFinder();
        $widget->setKey($key);
        
        $reflection = new \ReflectionProperty($widget, 'key');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод PurchasesSessionFinder::find
     * если пуст PurchasesSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $finder = new PurchasesSessionFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод PurchasesSessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('key_test', [['id_product'=>1, 'quantity'=>4, 'price'=>129.8]]);
        
        $finder = new PurchasesSessionFinder();
        
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 'key_test');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $collection);
        $this->assertFalse($collection->isEmpty());
        foreach($collection as $item) {
            $this->assertInstanceOf(PurchasesModel::class, $item);
            $this->assertNotEmpty($item->toArray());
            $this->assertSame(['id_product'=>1, 'quantity'=>4, 'price'=>129.8], $item->toArray());
        }
        
        $session->remove('key_test');
        $session->close();
    }
}
