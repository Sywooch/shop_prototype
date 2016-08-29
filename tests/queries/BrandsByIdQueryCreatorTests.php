<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\BrandsByIdQueryCreator;

/**
 * Тестирует класс app\queries\BrandsByIdQueryCreator
 */
class BrandsByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 78;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'model'=> new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new BrandsByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `brands`.`id`, `brands`.`brand` FROM `brands` WHERE `brands`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
