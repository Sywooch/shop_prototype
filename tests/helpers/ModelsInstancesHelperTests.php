<?php

namespace app\tests\helpers;

use app\helpers\ModelsInstancesHelper;
use app\models\FiltersModel;
use app\models\ProductsModel;
use app\models\UsersModel;
use app\models\CurrencyModel;
use app\models\CommentsModel;

/**
 * Тестирует класс app\helpers\ModelsInstancesHelper
 */
class ModelsInstancesHelperTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует метод ModelsInstancesHelper::getInstancesArray
     */
    public function testGetInstancesArray()
    {
        $result = ModelsInstancesHelper::getInstancesArray();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertTrue(array_key_exists('filtersModel', $result));
        $this->assertTrue(array_key_exists('productsModel', $result));
        $this->assertTrue(array_key_exists('clearCartModel', $result));
        $this->assertTrue(array_key_exists('usersModelForLogout', $result));
        $this->assertTrue(array_key_exists('currencyModel', $result));
        $this->assertTrue(array_key_exists('commentsModel', $result));
        
        $this->assertTrue($result['filtersModel'] instanceof FiltersModel);
        $this->assertTrue($result['productsModel'] instanceof ProductsModel);
        $this->assertTrue($result['clearCartModel'] instanceof ProductsModel);
        $this->assertTrue($result['usersModelForLogout'] instanceof UsersModel);
        $this->assertTrue($result['currencyModel'] instanceof CurrencyModel);
        $this->assertTrue($result['commentsModel'] instanceof CommentsModel);
    }
}
