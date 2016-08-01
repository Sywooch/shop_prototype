<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductsSphynxQueryCreator;

/**
 * Тестирует класс app\queries\ProductsSphynxQueryCreator
 */
class ProductsSphynxQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_config = [
        'tableName'=>'shop',
        'fields'=>['id'],
    ];
    
    /**
     * Тестирует создание строки SQL запроса с фильтром по параметру search ProductsSphynxQueryCreator::getSelectQuery()
     */
    public function testGetSelectQuery()
    {
        $_GET = ['search'=>'пиджак'];
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsSphynxQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT id FROM shop WHERE MATCH(:' . \Yii::$app->params['sphynxKey'] . ')';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
