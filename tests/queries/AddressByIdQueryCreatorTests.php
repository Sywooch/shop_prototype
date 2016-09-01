<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\AddressByIdQueryCreator;

/**
 * Тестирует класс app\queries\AddressByIdQueryCreator
 */
class AddressByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 6;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'address',
            'fields'=>['id', 'address', 'city', 'country', 'postcode'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new AddressByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `address`.`id`, `address`.`address`, `address`.`city`, `address`.`country`, `address`.`postcode` FROM `address` WHERE `address`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
