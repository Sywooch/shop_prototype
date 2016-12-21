<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CurrencySetService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\models\CurrencyModel;
use app\controllers\ProductsListController;
use yii\helpers\Url;
use app\helpers\HashHelper;

/**
 * Тестирует класс CurrencySetService
 */
class CurrencySetServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод CurrencySetService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $url = Url::current();
        
        $request = [
            'ChangeCurrencyForm'=>[
                'id'=>1, 'url'=>$url
            ],
        ];
        
        $service = new CurrencySetService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertSame($url, $result);
        
        $key = HashHelper::createCurrencyKey();
        
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->get($key);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('exchange_rate', $result);
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
