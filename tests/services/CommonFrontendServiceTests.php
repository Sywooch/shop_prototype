<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CommonFrontendService;
use app\models\CurrencyModel;
use yii\web\User;
use app\collections\{CollectionInterface,
    SessionCollectionInterface};
use app\widgets\PriceWidget;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture};
use app\forms\FormInterface;
use app\controllers\ProductsListController;

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
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
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
        $this->assertArrayHasKey('purchasesCollection', $result['cartConfig']);
        $this->assertArrayHasKey('priceWidget', $result['cartConfig']);
        $this->assertArrayHasKey('view', $result['cartConfig']);
        $this->assertInstanceOf(SessionCollectionInterface::class, $result['cartConfig']['purchasesCollection']);
        $this->assertInstanceOf(PriceWidget::class, $result['cartConfig']['priceWidget']);
        $this->assertInternalType('string', $result['cartConfig']['view']);
        
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('currencyCollection', $result['currencyConfig']);
        $this->assertArrayHasKey('form', $result['currencyConfig']);
        $this->assertArrayHasKey('view', $result['currencyConfig']);
        $this->assertInstanceOf(CollectionInterface::class, $result['currencyConfig']['currencyCollection']);
        $this->assertInstanceOf(FormInterface::class, $result['currencyConfig']['form']);
        $this->assertInternalType('string', $result['currencyConfig']['view']);
        
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('text', $result['searchConfig']);
        $this->assertArrayHasKey('view', $result['searchConfig']);
        $this->assertInternalType('string', $result['searchConfig']['text']);
        $this->assertInternalType('string', $result['searchConfig']['view']);
        
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('categoriesCollection', $result['menuConfig']);
        $this->assertInstanceOf(CollectionInterface::class, $result['menuConfig']['categoriesCollection']);
    }
    
    /**
     * Тестирует метод CommonFrontendService::handle
     * если валюта сохранена в СУБД
     */
    public function testHandleBaseCurrency()
    {
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
        $this->assertArrayHasKey('purchasesCollection', $result['cartConfig']);
        $this->assertArrayHasKey('priceWidget', $result['cartConfig']);
        $this->assertArrayHasKey('view', $result['cartConfig']);
        $this->assertInstanceOf(SessionCollectionInterface::class, $result['cartConfig']['purchasesCollection']);
        $this->assertInstanceOf(PriceWidget::class, $result['cartConfig']['priceWidget']);
        $this->assertInternalType('string', $result['cartConfig']['view']);
        
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('currencyCollection', $result['currencyConfig']);
        $this->assertArrayHasKey('form', $result['currencyConfig']);
        $this->assertArrayHasKey('view', $result['currencyConfig']);
        $this->assertInstanceOf(CollectionInterface::class, $result['currencyConfig']['currencyCollection']);
        $this->assertInstanceOf(FormInterface::class, $result['currencyConfig']['form']);
        $this->assertInternalType('string', $result['currencyConfig']['view']);
        
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('text', $result['searchConfig']);
        $this->assertArrayHasKey('view', $result['searchConfig']);
        $this->assertInternalType('string', $result['searchConfig']['text']);
        $this->assertInternalType('string', $result['searchConfig']['view']);
        
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('categoriesCollection', $result['menuConfig']);
        $this->assertInstanceOf(CollectionInterface::class, $result['menuConfig']['categoriesCollection']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
