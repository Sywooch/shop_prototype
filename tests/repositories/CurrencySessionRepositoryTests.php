<?php

namespace app\tests\repositories;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\repository\CurrencySessionRepository;
use app\models\CurrencyModel;

class CurrencySessionRepositoryTests extends TestCase
{
    private static $dbClass;
    private static $key = 'testKey';
    private static $wrongKey = 'wrongKey';
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        $fixture = self::$dbClass->currency['currency_2'];
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set(self::$key, $fixture);
        $session->close();
    }
    
    /**
     * Тестирует метод CurrencySessionRepository::getOne
     */
    public function testGetOne()
    {
        $repository = new CurrencySessionRepository();
        $result = $repository->getOne(self::$key);
        
        $this->assertTrue($result instanceof CurrencyModel);
    }
    
    /**
     * Тестирует метод CurrencySessionRepository::getGroup
     * при отсутствии данных, удовлетворяющих условиям SQL запроса
     */
    public function testGetGroupCriteriaNull()
    {
        $repository = new CurrencySessionRepository();
        $result = $repository->getOne(self::$wrongKey);
        
        $this->assertNull($result);
    }
    
    public static function tearDownAfterClass()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->remove(self::$key);
        $session->close();
        
        self::$dbClass->unloadFixtures();
    }
}
