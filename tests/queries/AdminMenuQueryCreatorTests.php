<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\AdminMenuQueryCreator;

/**
 * Тестирует класс app\queries\AdminMenuQueryCreator
 */
class AdminMenuQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'admin_menu',
            'fields'=>['id', 'name', 'route'],
        ]);
        
        $queryCreator = new AdminMenuQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `admin_menu`.`id`, `admin_menu`.`name`, `admin_menu`.`route` FROM `admin_menu`";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
