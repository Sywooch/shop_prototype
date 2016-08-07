<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\PurchasesInsertQueryCreator;

/**
 * Тестирует класс app\queries\PurchasesInsertQueryCreator
 */
class PurchasesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'purchases',
            'fields'=>['id_users', 'id_products', 'quantity', 'id_colors', 'id_sizes', 'id_deliveries', 'id_payments', 'received', 'received_date'],
            'objectsArray'=>[
                new MockModel([
                    'id_users'=>self::$_id, 
                    'id_products'=>self::$_id, 
                    'quantity'=>self::$_id,
                    'id_colors'=>self::$_id, 
                    'id_sizes'=>self::$_id,
                    'id_deliveries'=>self::$_id, 
                    'id_payments'=>self::$_id,
                    'received'=>self::$_id,
                    'received_date'=>self::$_id
                ]),
            ],
        ]);
        
        $queryCreator = new PurchasesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{purchases}} (id_users,id_products,quantity,id_colors,id_sizes,id_deliveries,id_payments,received,received_date) VALUES (:0_id_users,:0_id_products,:0_quantity,:0_id_colors,:0_id_sizes,:0_id_deliveries,:0_id_payments,:0_received,:0_received_date)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
