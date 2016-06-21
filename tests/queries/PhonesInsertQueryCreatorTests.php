<?php

namespace app\queries;

use app\tests\MockObject;
use app\tests\MockModel;
use app\queries\PhonesInsertQueryCreator;

/**
 * Тестирует класс app\queries\PhonesInsertQueryCreator
 */
class PhonesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'phones',
            'fields'=>['phone'],
            'objectsArray'=>[
                new MockModel(['phone'=>'+380683658978'])
            ],
        ]);
        
        $queryCreator = new PhonesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{phones}} (phone) VALUES (:0_phone)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
