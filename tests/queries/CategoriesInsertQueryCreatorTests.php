<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\CategoriesInsertQueryCreator;

/**
 * Тестирует класс app\queries\CategoriesInsertQueryCreator
 */
class CategoriesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_name = 'Очки';
    private static $_seocode = 'glasses';
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'categories',
            'fields'=>['name', 'seocode'],
            'objectsArray'=>[
                new MockModel([
                    'name'=>self::$_name, 
                    'seocode'=>self::$_seocode
                ])
            ],
        ]);
        
        $queryCreator = new CategoriesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{categories}} (name,seocode) VALUES (:0_name,:0_seocode)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
