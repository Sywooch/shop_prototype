<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\SubcategoryByIdQueryCreator;

/**
 * Тестирует класс app\queries\SubcategoryByIdQueryCreator
 */
class SubcategoryByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 8;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'subcategory',
            'fields'=>['id', 'name', 'seocode', 'id_categories'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new SubcategoryByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `subcategory`.`id`, `subcategory`.`name`, `subcategory`.`seocode`, `subcategory`.`id_categories` FROM `subcategory` WHERE `subcategory`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
