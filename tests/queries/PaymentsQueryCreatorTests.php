<?php

namespace app\queries;

use app\tests\MockObject;
use app\queries\PaymentsQueryCreator;

/**
 * Тестирует класс app\queries\PaymentsQueryCreator
 */
class PaymentsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'payments',
            'fields'=>['id', 'name', 'description'],
        ]);
        
        $queryCreator = new PaymentsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `payments`.`id`, `payments`.`name`, `payments`.`description` FROM `payments`";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
