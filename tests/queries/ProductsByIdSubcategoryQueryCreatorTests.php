<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\ProductsByIdSubcategoryQueryCreator;

/**
 * Тестирует класс app\queries\ProductsByIdSubcategoryQueryCreator
 */
class ProductsByIdSubcategoryQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 89;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products',
            'fields'=>['id', 'date', 'code', 'name', 'description', 'price', 'images', 'id_categories', 'id_subcategory'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new ProductsByIdSubcategoryQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `products`.`id`, `products`.`date`, `products`.`code`, `products`.`name`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory` FROM `products` WHERE `products`.`id_subcategory`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
