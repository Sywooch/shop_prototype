<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\PaymentsByIdQueryCreator;

/**
 * Тестирует класс app\queries\PaymentsByIdQueryCreator
 */
class PaymentsByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 9;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'payments',
            'fields'=>['id', 'name', 'description'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new PaymentsByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `payments`.`id`, `payments`.`name`, `payments`.`description` FROM `payments` WHERE `payments`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
