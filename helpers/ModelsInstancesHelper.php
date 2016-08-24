<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;
use app\models\{ProductsModel,
    UsersModel,
    CurrencyModel,
    CommentsModel,
    BrandsModel,
    ColorsModel,
    SizesModel,
    MailingListModel};

/**
 * Предоставляет методы для создания экземпляров моделей
 */
class ModelsInstancesHelper
{
    use ExceptionsTrait;
    
    private static $_instancesArray = array();
    
    /**
     * Возвращает массив экземпляров моделей для рендеринга
     */
    public static function getInstancesArray()
    {
        try {
            self::$_instancesArray['filtersModel'] = \Yii::$app->filters;
            self::$_instancesArray['currencyModel'] = \Yii::$app->shopUser->currency;
            self::$_instancesArray['categoriesList'] = MappersHelper::getCategoriesList();
            self::$_instancesArray['currencyList'] = MappersHelper::getСurrencyList();
            self::$_instancesArray['productsModelForCart'] = new ProductsModel(['scenario'=>ProductsModel::GET_FOR_CART]);
            self::$_instancesArray['clearCartModel'] = new ProductsModel(['scenario'=>ProductsModel::GET_FOR_CLEAR_CART]);
            self::$_instancesArray['usersModelForLogout'] = new UsersModel(['scenario'=>UsersModel::GET_FOR_LOGOUT]);
            self::$_instancesArray['mailingListModelForMailingForm'] = new MailingListModel(['scenario'=>MailingListModel::GET_FOR_SUBSCRIPTION]);
            self::$_instancesArray['adminMenuList'] = MappersHelper::getAdminMenuList();
            return self::$_instancesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
