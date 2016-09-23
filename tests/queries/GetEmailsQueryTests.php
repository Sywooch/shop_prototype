<?php

namespace app\tests\queries;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\queries\GetEmailsQuery;
use app\models\EmailsModel;

/**
 * Тестирует класс app\queries\GetEmailsQuery
 */
class GetEmailsQueryTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>'app\tests\source\fixtures\EmailsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод GetEmailsQuery::getOne()
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->emails['email_1'];
        
        $emailsQuery = new GetEmailsQuery([
            'fields'=>['id', 'email'],
            'extraWhere'=>['emails.email'=>$fixture['email']]
        ]);
        
        $query = $emailsQuery->getOne();
        $queryRaw = clone $query;
        
        $expectQuery = sprintf("SELECT `emails`.`id`, `emails`.`email` FROM `emails` WHERE `emails`.`email`='%s'", $fixture['email']);
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $query->one();
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof EmailsModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
