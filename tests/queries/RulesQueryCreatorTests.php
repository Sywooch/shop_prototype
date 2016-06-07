<?php

namespace app\queries;

use app\mappers\RulesMapper;
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
        $rulesMapper = new RulesMapper([
            'tableName'=>'rules',
            'fields'=>['id', 'rule'],
            'orderByField'=>'rule',
        ]);
        
        $rulesMapper->visit(new RulesQueryCreator());
        
        $query = 'SELECT [[rules.id]],[[rules.rule]] FROM {{rules}}';
        
        $this->assertEquals($query, $rulesMapper->query);
    }
}
