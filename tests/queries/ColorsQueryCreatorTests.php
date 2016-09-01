<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ColorsQueryCreator;

/**
 * Тестирует класс app\queries\ColorsQueryCreator
 */
class ColorsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new ColorsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `colors`.`id`, `colors`.`color` FROM `colors`";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
