<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\SizesForProductQueryCreator;

/**
 * Тестирует класс app\queries\SizesForProductQueryCreator
 */
class SizesForProductQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 9;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new SizesForProductQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `sizes`.`id`, `sizes`.`size` FROM `sizes` INNER JOIN `products_sizes` ON `sizes`.`id`=`products_sizes`.`id_sizes` WHERE `products_sizes`.`id_products`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
