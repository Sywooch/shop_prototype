<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;
use app\models\ProductsModel;
use app\models\UsersModel;
use app\models\CurrencyModel;
use app\models\CommentsModel;

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
            self::$_instancesArray['productsModel'] = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
            self::$_instancesArray['clearCartModel'] = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_CLEAR_CART]);
            self::$_instancesArray['usersModelForLogout'] = new UsersModel(['scenario'=>UsersModel::GET_FROM_LOGOUT_FORM]);
            self::$_instancesArray['currencyModel'] = \Yii::$app->user->currency;
            self::$_instancesArray['commentsModel'] = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
            return self::$_instancesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
