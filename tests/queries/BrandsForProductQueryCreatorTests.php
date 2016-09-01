<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\BrandsForProductQueryCreator;

/**
 * Тестирует класс app\queries\BrandsForProductQueryCreator
 */
class BrandsForProductQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 12;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new BrandsForProductQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `brands`.`id`, `brands`.`brand` FROM `brands` INNER JOIN `products_brands` ON `brands`.`id`=`products_brands`.`id_brands` WHERE `products_brands`.`id_products`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
