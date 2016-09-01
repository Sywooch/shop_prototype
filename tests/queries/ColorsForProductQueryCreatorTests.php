<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\ColorsForProductQueryCreator;

/**
 * Тестирует класс app\queries\ColorsForProductQueryCreator
 */
class ColorsForProductQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id=17;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new ColorsForProductQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_colors` WHERE `products_colors`.`id_products`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
