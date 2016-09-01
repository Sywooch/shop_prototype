<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\PhonesByIdQueryCreator;

/**
 * Тестирует класс app\queries\PhonesByIdQueryCreator
 */
class PhonesByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 8;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'phones',
            'fields'=>['id', 'phone'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new PhonesByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `phones`.`id`, `phones`.`phone` FROM `phones` WHERE `phones`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
