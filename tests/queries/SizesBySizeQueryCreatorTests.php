<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\SizesBySizeQueryCreator;

/**
 * Тестирует класс app\queries\SizesBySizeQueryCreator
 */
class SizesBySizeQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_size = 46;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'model'=>new MockModel(['size'=>self::$_size])
        ]);
        
        $queryCreator = new SizesBySizeQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `sizes`.`id`, `sizes`.`size` FROM `sizes` WHERE `sizes`.`size`=" . self::$_size;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
