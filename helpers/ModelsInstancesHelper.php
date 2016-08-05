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
            self::$_instancesArray['productsModelForAddToCart'] = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
            self::$_instancesArray['brandsModelForAddToCart'] = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_ADD_PRODUCT_FORM]);
            self::$_instancesArray['colorsModelForAddToCart'] = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_ADD_PRODUCT_FORM]);
            self::$_instancesArray['sizesModelForAddToCart'] = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT_FORM]);
            self::$_instancesArray['clearCartModel'] = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_CLEAR_CART]);
            self::$_instancesArray['usersModelForLogout'] = new UsersModel(['scenario'=>UsersModel::GET_FROM_LOGOUT_FORM]);
            self::$_instancesArray['commentsModel'] = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
            self::$_instancesArray['mailingListModelForMailingForm'] = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_MAILING_FORM]);
            return self::$_instancesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
