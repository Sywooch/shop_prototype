<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\SubcategoryInsertQueryCreator;

/**
 * Тестирует класс app\queries\SubcategoryInsertQueryCreator
 */
class SubcategoryInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_name = 'Очки';
    private static $_seocode = 'glasses';
    private static $_id_categories = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'subcategory',
            'fields'=>['name', 'seocode', 'id_categories'],
            'objectsArray'=>[
                new MockModel([
                    'name'=>self::$_name, 
                    'seocode'=>self::$_seocode,
                    'id_categories'=>self::$_id_categories
                ])
            ],
        ]);
        
        $queryCreator = new SubcategoryInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{subcategory}} (name,seocode,id_categories) VALUES (:0_name,:0_seocode,:0_id_categories)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
