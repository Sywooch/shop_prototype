<?php

namespace app\tests\helpers;

use app\tests\DbManager;
use app\helpers\{MappersHelper,
    ModelsInstancesHelper};
use app\models\{AdminMenuModel,
    BrandsModel,
    CategoriesModel,
    ColorsModel,
    CommentsModel,
    CurrencyModel,
    FiltersModel,
    MailingListModel,
    ProductsModel,
    SearchModel,
    SizesModel,
    SubcategoryModel,
    UsersModel};

/**
 * Тестирует класс app\helpers\ModelsInstancesHelper
 */
class ModelsInstancesHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_currency = 'EUR';
    private static $_exchange_rate = '12.456';
    private static $_main = '1';
    private static $_name = 'Some Name';
    private static $_categorySeocode = 'mensfootwear';
    private static $_route = 'some/some';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{currency}} SET [[id]]=:id, [[currency]]=:currency, [[exchange_rate]]=:exchange_rate, [[main]]=:main');
        $command->bindValues([':id'=>self::$_id, ':currency'=>self::$_currency, ':exchange_rate'=>self::$_exchange_rate, ':main'=>self::$_main]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{admin_menu}} SET [[id]]=:id, [[name]]=:name, [[route]]=:route');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name,  ':route'=>self::$_route]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод ModelsInstancesHelper::getInstancesArray
     */
    public function testGetInstancesArray()
    {
        $result = ModelsInstancesHelper::getInstancesArray();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertTrue(array_key_exists('filtersModel', $result));
        $this->assertTrue(array_key_exists('productsForCartModel', $result));
        $this->assertTrue(array_key_exists('clearCartModel', $result));
        $this->assertTrue(array_key_exists('usersForLogoutModel', $result));
        $this->assertTrue(array_key_exists('currencyModel', $result));
        $this->assertTrue(array_key_exists('mailingListForMailingFormModel', $result));
        $this->assertTrue(array_key_exists('categoriesList', $result));
        $this->assertTrue(array_key_exists('currencyList', $result));
        $this->assertTrue(array_key_exists('adminMenuList', $result));
        $this->assertTrue(array_key_exists('categoriesForCurrencyModel', $result));
        $this->assertTrue(array_key_exists('subcategoryForCurrencyModel', $result));
        $this->assertTrue(array_key_exists('productsForCurrencyModel', $result));
        $this->assertTrue(array_key_exists('searchForCurrencyModel', $result));
        
        $this->assertTrue($result['filtersModel'] instanceof FiltersModel);
        $this->assertTrue($result['productsForCartModel'] instanceof ProductsModel);
        $this->assertTrue($result['clearCartModel'] instanceof ProductsModel);
        $this->assertTrue($result['usersForLogoutModel'] instanceof UsersModel);
        $this->assertTrue($result['currencyModel'] instanceof CurrencyModel);
        $this->assertTrue($result['mailingListForMailingFormModel'] instanceof MailingListModel);
        $this->assertTrue($result['categoriesList'][0] instanceof CategoriesModel);
        $this->assertTrue($result['currencyList'][0] instanceof CurrencyModel);
        $this->assertTrue($result['adminMenuList'][0] instanceof AdminMenuModel);
        $this->assertTrue($result['categoriesForCurrencyModel'] instanceof CategoriesModel);
        $this->assertTrue($result['subcategoryForCurrencyModel'] instanceof SubcategoryModel);
        $this->assertTrue($result['productsForCurrencyModel'] instanceof ProductsModel);
        $this->assertTrue($result['searchForCurrencyModel'] instanceof SearchModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
