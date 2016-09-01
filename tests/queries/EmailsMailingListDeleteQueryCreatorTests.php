<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\EmailsMailingListDeleteQueryCreator;

/**
 * Тестирует класс app\queries\EmailsMailingListDeleteQueryCreator
 */
class EmailsMailingListDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_params = [
        'id_email'=>[1, 23], 
        'id_mailing_list'=>[2, 32]
    ];
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'emails_mailing_list',
            'fields'=>['id_email', 'id_mailing_list'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new EmailsMailingListDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "DELETE FROM `emails_mailing_list` WHERE (`id_email` IN (" . implode(', ', self::$_params['id_email']) . ")) AND (`id_mailing_list` IN (" . implode(', ', self::$_params['id_mailing_list']) . "))";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
}
