<?php

namespace app\queries;

use app\tests\MockObject;
use app\queries\RulesQueryCreator;

/**
 * Тестирует класс app\queries\RulesQueryCreator
 */
class RulesQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'rules',
            'fields'=>['id', 'rule'],
        ]);
        
        $queryCreator = new RulesQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[rules.id]],[[rules.rule]] FROM {{rules}}';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
