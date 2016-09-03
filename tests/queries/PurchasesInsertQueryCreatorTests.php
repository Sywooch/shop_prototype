<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\PurchasesInsertQueryCreator;

/**
 * Тестирует класс app\queries\PurchasesInsertQueryCreator
 */
class PurchasesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[1, 23, 34, 2, 5, 645, 2, 1, 1456987854]];
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'purchases',
            'fields'=>['id_users', 'id_products', 'quantity', 'id_colors', 'id_sizes', 'id_deliveries', 'id_payments', 'received', 'received_date'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new PurchasesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `purchases` (`id_users`, `id_products`, `quantity`, `id_colors`, `id_sizes`, `id_deliveries`, `id_payments`, `received`, `received_date`) VALUES (" . implode(', ', array_slice(self::$_params[0], 0, -1)) . ", '" . array_pop(self::$_params[0]) . "')";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
