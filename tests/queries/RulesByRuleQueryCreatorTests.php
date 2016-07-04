<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\RulesByRuleQueryCreator;

/**
 * Тестирует класс app\queries\RulesByRuleQueryCreator
 */
class RulesByRuleQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new RulesByRuleQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[rules.id]],[[rules.rule]] FROM {{rules}} WHERE [[rules.rule]]=:rule';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
