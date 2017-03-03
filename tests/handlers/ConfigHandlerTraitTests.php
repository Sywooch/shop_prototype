<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\User;
use yii\base\Model;
use app\handlers\ConfigHandlerTrait;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture,
    UsersFixture};
use app\models\{AbstractBaseModel,
    CategoriesModel,
    CurrencyInterface,
    CurrencyModel,
    UsersModel};
use app\collections\{CollectionInterface,
    LightPagination,
    PaginationInterface,
    ProductsCollection,
    PurchasesCollection,
    PurchasesCollectionInterface};
use app\forms\AbstractBaseForm;
use app\controllers\ProductsListController;
use app\exceptions\ExceptionsTrait;

/**
 * Тестирует класс ConfigHandlerTrait
 */
class ConfigHandlerTraitTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'categories'=>CategoriesFixture::class,
                'users'=>UsersFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new ProductsListController('list', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new class() {
            use ConfigHandlerTrait, ExceptionsTrait;
        };
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::userInfoWidgetConfig
     */
    public function testUserInfoWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'userInfoWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, \Yii::$app->user);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::shortCartWidgetConfig
     */
    public function testShortCartWidgetConfig()
    {
        $currencyModel = new class() extends CurrencyModel {};
        $ordersCollection = new class() extends PurchasesCollection {};
        
        $reflection = new \ReflectionMethod($this->handler, 'shortCartWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $ordersCollection, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::currencyWidgetConfig
     */
    public function testCurrencyWidgetConfig()
    {
        $currencyArray = [new class() extends CurrencyModel {}];
        $changeCurrencyForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'currencyWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $currencyArray, $changeCurrencyForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['currency']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::searchWidgetConfig
     */
    public function testSearchWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'searchWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, 'search');
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['text']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::categoriesMenuWidgetConfig
     */
    public function testCategoriesMenuWidgetConfig()
    {
        $categoriesModelArray = [new class() extends CategoriesModel {}];
        
        $reflection = new \ReflectionMethod($this->handler, 'categoriesMenuWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $categoriesModelArray);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('categories', $result);
        $this->assertInternalType('array', $result['categories']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::accountMailingsUnsubscribeWidgetConfig
     */
    public function testAccountMailingsUnsubscribeWidgetConfig()
    {
        $mailingsArray = [new class() {}];
        $mailingForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'accountMailingsUnsubscribeWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingsArray, $mailingForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::accountMailingsFormWidgetConfig
     */
    public function testAccountMailingsFormWidgetConfig()
    {
        $mailingsArray = [new class() {}];
        $mailingForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'accountMailingsFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingsArray, $mailingForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::оrdersFiltersWidgetConfig
     */
    public function testOrdersFiltersWidgetConfig()
    {
        $sortingTypesArray = [new class() {}];
        $statusesArray = [new class() {}];
        $ordersFiltersForm = new class() extends AbstractBaseForm {
            public $sortingType;
            public $dateFrom;
            public $dateTo;
            public $url;
        };
        
        $reflection = new \ReflectionMethod($this->handler, 'оrdersFiltersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $sortingTypesArray, $statusesArray, $ordersFiltersForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('sortingTypes', $result);
        $this->assertArrayhasKey('statuses', $result);
        $this->assertArrayhasKey('form', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInternalType('array', $result['statuses']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::paginationWidgetConfig
     */
    public function testPaginationWidgetConfig()
    {
        $pagination = new class() extends LightPagination {};
        
        $reflection = new \ReflectionMethod($this->handler, 'paginationWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $pagination);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('pagination', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(PaginationInterface::class, $result['pagination']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::shortCartWidgetAjaxConfig
     * если запрос с ошибками
     */
    public function testShortCartWidgetAjaxConfig()
    {
        $purchasesCollection = new class() extends PurchasesCollection {};
        $currentCurrencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'shortCartWidgetAjaxConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $purchasesCollection, $currentCurrencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::cartWidgetConfig
     * если запрос с ошибками
     */
    public function testCartWidgetConfig()
    {
        $purchasesCollection = new class() extends PurchasesCollection {};
        $currentCurrencyModel = new class() extends CurrencyModel {};
        $form = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'cartWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $purchasesCollection, $currentCurrencyModel, $form);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::shortCartRedirectWidgetConfig
     * если запрос с ошибками
     */
    public function testShortCartRedirectWidgetConfig()
    {
        $purchasesCollection = new class() extends PurchasesCollection {};
        $currentCurrencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'shortCartRedirectWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $purchasesCollection, $currentCurrencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::emptyProductsWidgetConfig
     */
    public function testEmptyProductsWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'emptyProductsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('template', $result);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::productsWidgetConfig
     */
    public function testProductsWidgetConfig()
    {
        $productsCollection = new class() extends ProductsCollection {};
        $currencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'productsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $productsCollection, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(CollectionInterface::class, $result['products']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::unsubscribeEmptyWidgetConfig
     */
    public function testUnsubscribeEmptyWidgetConfig()
    {
        $email = 'mail@mail.com';
        
        $reflection = new \ReflectionMethod($this->handler, 'unsubscribeEmptyWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $email);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['email']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::adminAddProductFormWidgetConfig
     */
    public function testAminAddProductFormWidgetConfig()
    {
        $categoriesArray = [new class() {
            public $id = 1;
            public $name = 'category';
        }];
        
        $colorsArray = [new class() {
            public $id = 1;
            public $color = 'color';
        }];
        
        $sizesArray = [new class() {
            public $id = 1;
            public $size = 'size';
        }];
        
        $brandsArray = [new class() {
            public $id = 1;
            public $brand = 'brand';
        }];
        
        $adminProductForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminAddProductFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $categoriesArray, $colorsArray, $sizesArray, $brandsArray, $adminProductForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('categories', $result);
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('brands', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['categories']);
        $this->assertInternalType('array', $result['colors']);
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInternalType('array', $result['brands']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::adminCategoriesWidgetConfig
     */
    public function testAdminCategoriesWidgetConfig()
    {
        $categoriesModelArray = [new class() {}];
        $categoriesForm = new class() extends AbstractBaseForm {};
        $subcategoryForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCategoriesWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $categoriesModelArray, $categoriesForm, $subcategoryForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('categories', $result);
        $this->assertArrayHasKey('categoriesForm', $result);
        $this->assertArrayHasKey('subcategoryForm', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['categories']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['categoriesForm']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['subcategoryForm']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::categoriesOptionWidgetConfig
     */
    public function testCategoriesOptionWidgetConfig()
    {
        $categoriesModelArray = [new class() {}];
        
        $reflection = new \ReflectionMethod($this->handler, 'categoriesOptionWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $categoriesModelArray);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('categories', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['categories']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::adminBrandsWidgetConfig
     */
    public function testAdminBrandsWidgetConfig()
    {
        $brandsModelArray = [new class() {}];
        $brandsForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminBrandsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $brandsModelArray, $brandsForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('brands', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('brandsForm', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['brands']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['brandsForm']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::adminColorsWidgetConfig
     */
    public function testAdminColorsWidgetConfig()
    {
        $colorsModelArray = [new class() {}];
        $colorsForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminColorsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $colorsModelArray, $colorsForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['colors']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::adminSizesWidgetConfig
     */
    public function testAdminSizesWidgetConfig()
    {
        $sizesModelArray = [new class() {}];
        $sizesForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminSizesWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $sizesModelArray, $sizesForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::accountContactsWidgetConfig
     */
    public function testAccountContactsWidgetConfig()
    {
        $usersModel = new class() extends Model {};
        
        $reflection = new \ReflectionMethod($this->handler, 'accountContactsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $usersModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('user', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(Model::class, $result['user']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::accountCurrentOrdersWidgetConfig
     */
    public function testAccountCurrentOrdersWidgetConfig()
    {
        $purchasesArray = [new class() {}];
        $currencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'accountCurrentOrdersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $purchasesArray, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('purchases', $result);
        $this->assertArrayhasKey('currency', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('array', $result['purchases']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::accountMailingsWidgetConfig
     */
    public function testAccountMailingsWidgetConfig()
    {
        $mailingsArray = [new class() {}];
        
        $reflection = new \ReflectionMethod($this->handler, 'accountMailingsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingsArray);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('mailings', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::adminUserDetailBreadcrumbsWidgetConfig
     */
    public function testAdminUserDetailBreadcrumbsWidgetConfig()
    {
        $usersModel = new class() extends Model {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminUserDetailBreadcrumbsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $usersModel);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('usersModel', $result);
        $this->assertInstanceOf(Model::class, $result['usersModel']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::adminUserMenuWidgetConfig
     */
    public function testAdminUserMenuWidgetConfig()
    {
        $usersModel = new class() extends Model {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminUserMenuWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $usersModel);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('usersModel', $result);
        $this->assertInstanceOf(Model::class, $result['usersModel']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::accountOrdersWidgetConfig
     */
    public function testAccountOrdersWidgetConfig()
    {
        $ordersArray = [new class() {}];
        $currentCurrencyModel = new class() extends CurrencyModel {};
        $purchaseForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'accountOrdersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $ordersArray, $purchaseForm, $currentCurrencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('purchases', $result);
        $this->assertArrayhasKey('currency', $result);
        $this->assertArrayhasKey('form', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('array', $result['purchases']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::accountChangeDataWidgetConfig
     */
    public function testAccountChangeDataWidgetConfig()
    {
        $userUpdateForm = new class() extends AbstractBaseForm {
            public $name;
            public $surname;
            public $phone;
            public $address;
            public $city;
            public $country;
            public $postcode;
        };
        
        $usersModel = UsersModel::findOne(1);
        
        $reflection = new \ReflectionMethod($this->handler, 'accountChangeDataWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $userUpdateForm, $usersModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('form', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::accountChangePasswordWidgetConfig
     */
    public function testAccountChangePasswordWidgetConfig()
    {
        $userChangePasswordForm = new class() extends AbstractBaseForm{};
        
        $reflection = new \ReflectionMethod($this->handler, 'accountChangePasswordWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $userChangePasswordForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('form', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::adminUserMailingsUnsubscribeWidgetConfig
     */
    public function testAdminUserMailingsUnsubscribeWidgetConfig()
    {
        $mailingsArray = [new class() {}];
        $mailingForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminUserMailingsUnsubscribeWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingsArray, $mailingForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::adminUserMailingsFormWidgetConfig
     */
    public function testAdminUserMailingsFormWidgetConfig()
    {
        $mailingsArray = [new class() {}];
        $mailingForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminUserMailingsFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingsArray, $mailingForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::adminCurrencyWidgetConfig
     */
    public function testAdminCurrencyWidgetConfig()
    {
        $currencyModelArray = [new class() {}];
        $currencyForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCurrencyWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $currencyModelArray, $currencyForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['currency']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::adminDeliveriesWidgetConfig
     */
    public function testAdminDeliveriesWidgetConfig()
    {
        $deliveriesModelArray = [new class() {}];
        $currentCurrencyModel = new class() extends CurrencyModel {};
        $deliveriesForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminDeliveriesWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $deliveriesModelArray, $currentCurrencyModel, $deliveriesForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('deliveries', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['deliveries']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
