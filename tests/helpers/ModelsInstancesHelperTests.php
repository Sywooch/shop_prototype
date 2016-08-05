<?php

namespace app\tests\helpers;

use app\tests\DbManager;
use app\helpers\{ModelsInstancesHelper, MappersHelper};
use app\models\{FiltersModel,
    ProductsModel,
    UsersModel,
    CurrencyModel,
    CommentsModel,
    BrandsModel,
    ColorsModel,
    SizesModel,
    MailingListModel,
    CategoriesModel};

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
        $this->assertTrue(array_key_exists('productsModelForAddToCart', $result));
        $this->assertTrue(array_key_exists('clearCartModel', $result));
        $this->assertTrue(array_key_exists('usersModelForLogout', $result));
        $this->assertTrue(array_key_exists('currencyModel', $result));
        $this->assertTrue(array_key_exists('commentsModel', $result));
        $this->assertTrue(array_key_exists('brandsModelForAddToCart', $result));
        $this->assertTrue(array_key_exists('colorsModelForAddToCart', $result));
        $this->assertTrue(array_key_exists('sizesModelForAddToCart', $result));
        $this->assertTrue(array_key_exists('mailingListModelForMailingForm', $result));
        $this->assertTrue(array_key_exists('categoriesList', $result));
        $this->assertTrue(array_key_exists('currencyList', $result));
        
        $this->assertTrue($result['filtersModel'] instanceof FiltersModel);
        $this->assertTrue($result['productsModelForAddToCart'] instanceof ProductsModel);
        $this->assertTrue($result['clearCartModel'] instanceof ProductsModel);
        $this->assertTrue($result['usersModelForLogout'] instanceof UsersModel);
        $this->assertTrue($result['currencyModel'] instanceof CurrencyModel);
        $this->assertTrue($result['commentsModel'] instanceof CommentsModel);
        $this->assertTrue($result['brandsModelForAddToCart'] instanceof BrandsModel);
        $this->assertTrue($result['colorsModelForAddToCart'] instanceof ColorsModel);
        $this->assertTrue($result['sizesModelForAddToCart'] instanceof SizesModel);
        $this->assertTrue($result['mailingListModelForMailingForm'] instanceof MailingListModel);
        $this->assertTrue($result['categoriesList'][0] instanceof CategoriesModel);
        $this->assertTrue($result['currencyList'][0] instanceof CurrencyModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
