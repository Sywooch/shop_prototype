<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CommonFrontendService;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture};
use app\tests\DbManager;
use app\controllers\ProductDetailController;
use app\models\CurrencyModel;
use yii\web\User;
use app\collections\PurchasesCollectionInterface;
use app\forms\ChangeCurrencyForm;

/**
 * Тестирует класс CommonFrontendService
 */
class CommonFrontendServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'categories'=>CategoriesFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод CommonFrontendService::handle
     * если валюта сохранена в сессии
     */
    public function testHandleSessionCurrency()
    {
        \Yii::$app->controller = new ProductDetailController('product-detail', \Yii::$app);
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set(\Yii::$app->params['currencyKey'], ['code'=>'MONEY', 'exchange_rate'=>14.7654, 'main'=>true]);
        $session->close();
        
        $request = [\Yii::$app->params['searchKey']=>'some text'];
        
        $service = new CommonFrontendService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('currencyModel', $result);
        $this->assertInstanceOf(CurrencyModel::class, $result['currencyModel']);
        
        $this->assertArrayHasKey('userConfig', $result);
        $this->assertArrayHasKey('user', $result['userConfig']);
        $this->assertArrayHasKey('view', $result['userConfig']);
        $this->assertInstanceOf(User::class, $result['userConfig']['user']);
        $this->assertInternalType('string', $result['userConfig']['view']);
        
        $this->assertArrayHasKey('cartConfig', $result);
        $this->assertArrayHasKey('purchases', $result['cartConfig']);
        $this->assertArrayHasKey('currency', $result['cartConfig']);
        $this->assertArrayHasKey('view', $result['cartConfig']);
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['cartConfig']['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['cartConfig']['currency']);
        $this->assertInternalType('string', $result['cartConfig']['view']);
        
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('currency', $result['currencyConfig']);
        $this->assertArrayHasKey('form', $result['currencyConfig']);
        $this->assertArrayHasKey('view', $result['currencyConfig']);
        $this->assertInternalType('array', $result['currencyConfig']['currency']);
        $this->assertInstanceOf(ChangeCurrencyForm::class, $result['currencyConfig']['form']);
        $this->assertInternalType('string', $result['currencyConfig']['view']);
        
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('text', $result['searchConfig']);
        $this->assertArrayHasKey('view', $result['searchConfig']);
        $this->assertInternalType('string', $result['searchConfig']['text']);
        $this->assertInternalType('string', $result['searchConfig']['view']);
        
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('categories', $result['menuConfig']);
        $this->assertInternalType('array', $result['menuConfig']['categories']);
    }
    
    /**
     * Тестирует метод CommonFrontendService::handle
     * если валюта сохранена в СУБД
     */
    public function testHandleBaseCurrency()
    {
        \Yii::$app->controller = new ProductDetailController('product-detail', \Yii::$app);
        
        $session = \Yii::$app->session;
        $session->open();
        $session->remove(\Yii::$app->params['currencyKey']);
        $session->close();
        
        $request = [\Yii::$app->params['searchKey']=>'some text'];
        
        $service = new CommonFrontendService();
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('currencyModel', $result);
        $this->assertInstanceOf(CurrencyModel::class, $result['currencyModel']);
        
        $this->assertArrayHasKey('userConfig', $result);
        $this->assertArrayHasKey('user', $result['userConfig']);
        $this->assertArrayHasKey('view', $result['userConfig']);
        $this->assertInstanceOf(User::class, $result['userConfig']['user']);
        $this->assertInternalType('string', $result['userConfig']['view']);
        
        $this->assertArrayHasKey('cartConfig', $result);
        $this->assertArrayHasKey('purchases', $result['cartConfig']);
        $this->assertArrayHasKey('currency', $result['cartConfig']);
        $this->assertArrayHasKey('view', $result['cartConfig']);
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['cartConfig']['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['cartConfig']['currency']);
        $this->assertInternalType('string', $result['cartConfig']['view']);
        
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('currency', $result['currencyConfig']);
        $this->assertArrayHasKey('form', $result['currencyConfig']);
        $this->assertArrayHasKey('view', $result['currencyConfig']);
        $this->assertInternalType('array', $result['currencyConfig']['currency']);
        $this->assertInstanceOf(ChangeCurrencyForm::class, $result['currencyConfig']['form']);
        $this->assertInternalType('string', $result['currencyConfig']['view']);
        
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('text', $result['searchConfig']);
        $this->assertArrayHasKey('view', $result['searchConfig']);
        $this->assertInternalType('string', $result['searchConfig']['text']);
        $this->assertInternalType('string', $result['searchConfig']['view']);
        
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('categories', $result['menuConfig']);
        $this->assertInternalType('array', $result['menuConfig']['categories']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
