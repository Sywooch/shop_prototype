<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\SubcategoryUpdateQueryCreator;

/**
 * Тестирует класс app\queries\SubcategoryUpdateQueryCreator
 */
class SubcategoryUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_some = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'subcategory',
            'fields'=>['id', 'name', 'seocode', 'id_categories'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_some, 
                    'name'=>self::$_some, 
                    'seocode'=>self::$_some,
                    'id_categories'=>self::$_some, 
                ]),
            ],
        ]);
        
        $queryCreator = new SubcategoryUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{subcategory}} (id,name,seocode,id_categories) VALUES (:0_id,:0_name,:0_seocode,:0_id_categories) ON DUPLICATE KEY UPDATE name=VALUES(name),seocode=VALUES(seocode),id_categories=VALUES(id_categories)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
