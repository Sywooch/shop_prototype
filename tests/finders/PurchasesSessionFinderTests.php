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
     * Тестирует метод PurchasesSessionFinder::rules
     */
    public function testRules()
    {
        $finder = new PurchasesSessionFinder();
        $finder->validate();
        
        $this->assertNotEmpty($finder->errors);
        $this->assertCount(1, $finder->errors);
        $this->assertArrayHasKey('key', $finder->errors);
        
        $finder = new PurchasesSessionFinder();
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setValue($finder, 'key');
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод PurchasesSessionFinder::find
     */
    public function testFind()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set('key_test', [['id'=>1, 'quantity'=>4, 'price'=>129.8]]);
        
        $finder = new PurchasesSessionFinder();
        
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setValue($finder, 'key_test');
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $collection);
        $this->assertFalse($collection->isEmpty());
        foreach($collection as $item) {
            $this->assertInstanceOf(PurchasesModel::class, $item);
            $this->assertNotEmpty($item->toArray());
            $this->assertSame(['id'=>1, 'quantity'=>4, 'price'=>129.8], $item->toArray());
        }
        
        $session->remove('key_test');
        $session->close();
    }
}
