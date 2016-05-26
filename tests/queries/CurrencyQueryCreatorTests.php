<?php

namespace app\tests\queries;

use app\queries\CurrencyQueryCreator;
use app\mappers\CurrencyMapper;

/**
 * Тестирует класс app\queries\CurrencyQueryCreator
 */
class CurrencyQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $currencyMapper = new CurrencyMapper([
            'tableName'=>'currency',
            'fields'=>['id', 'currency'],
            'orderByField'=>'currency'
        ]);
        $currencyMapper->visit(new CurrencyQueryCreator());
        
        $query = 'SELECT [[currency.id]],[[currency.currency]] FROM {{currency}}';
        
        $this->assertEquals($query, $currencyMapper->query);
    }
}
