<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\PurchasesByIdUsersQueryCreator;

/**
 * Тестирует класс app\queries\PurchasesByIdUsersQueryCreator
 */
class PurchasesByIdUsersQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 33;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'purchases',
            'fields'=>['id', 'id_users', 'id_products', 'quantity', 'id_colors', 'id_ids', 'id_deliveries', 'id_payments', 'received', 'received_date', 'processed', 'canceled', 'shipped'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new PurchasesByIdUsersQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `purchases`.`id`, `purchases`.`id_users`, `purchases`.`id_products`, `purchases`.`quantity`, `purchases`.`id_colors`, `purchases`.`id_ids`, `purchases`.`id_deliveries`, `purchases`.`id_payments`, `purchases`.`received`, `purchases`.`received_date`, `purchases`.`processed`, `purchases`.`canceled`, `purchases`.`shipped` FROM `purchases` WHERE `purchases`.`id_users`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
