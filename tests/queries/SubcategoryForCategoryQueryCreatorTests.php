<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\SubcategoryForCategoryQueryCreator;

/**
 * Тестирует класс app\queries\SubcategoryForCategoryQueryCreator
 */
class SubcategoryForCategoryQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 2;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'subcategory',
            'fields'=>['id', 'name'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new SubcategoryForCategoryQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `subcategory`.`id`, `subcategory`.`name` FROM `subcategory` INNER JOIN `categories` ON `subcategory`.`id_categories`=`categories`.`id` WHERE `categories`.`id`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
