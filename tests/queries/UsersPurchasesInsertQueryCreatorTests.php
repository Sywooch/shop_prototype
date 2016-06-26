<?php

namespace app\queries;

use app\tests\MockObject;
use app\tests\MockModel;
use app\queries\UsersPurchasesInsertQueryCreator;

/**
 * Тестирует класс app\queries\UsersPurchasesInsertQueryCreator
 */
class UsersPurchasesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'users_purchases',
            'fields'=>['id_users', 'id_products', 'id_deliveries', 'id_payments'],
            'objectsArray'=>[
                new MockModel([
                    'id_users'=>1, 
                    'id_products'=>1, 
                    'id_deliveries'=>1, 
                    'id_payments'=>1
                ]),
            ],
        ]);
        
        $queryCreator = new UsersPurchasesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{users_purchases}} (id_users,id_products,id_deliveries,id_payments) VALUES (:0_id_users,:0_id_products,:0_id_deliveries,:0_id_payments)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
