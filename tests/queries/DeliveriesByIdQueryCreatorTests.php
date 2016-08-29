<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\DeliveriesByIdQueryCreator;

/**
 * Тестирует класс app\queries\DeliveriesByIdQueryCreator
 */
class DeliveriesByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 46;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'deliveries',
            'fields'=>['id', 'name', 'description', 'price'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new DeliveriesByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `deliveries`.`id`, `deliveries`.`name`, `deliveries`.`description`, `deliveries`.`price` FROM `deliveries` WHERE `deliveries`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
