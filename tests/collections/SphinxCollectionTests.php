<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\{SphinxCollection,
    CollectionInterface};
use yii\sphinx\{MatchExpression,
    Query};

class SphinxCollectionTests extends TestCase
{
    /**
     * Тестирует метод SphinxCollection::getArrays
     */
    public function testGetArrays()
    {
        $query = new Query();
        $query->select(['id']);
        $query->from('{{shop}}');
        $query->match(new MatchExpression('[[@* :search]]', ['search'=>'adidas']));
        
        $collection = new SphinxCollection();
        
        $reflection = new \ReflectionProperty($collection, 'query');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, $query);
        
        $result = $collection->getArrays();
        
        $this->assertInstanceOf(CollectionInterface::class, $result);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result[0]);
    }
}
