<?php

namespace app\helpers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\models\{CategoriesModel,
    CurrencyModel};

/**
 * Коллекция методов для создания объектов,
 * вызываемых без изменений в разных блоках кода
 */
class InstancesHelper
{
    private static $_instancesArray = [];
    
    /**
     * Конструирует объекты для рендеринга
     * @return array
     */
    public static function getInstances(): array
    {
        try {
            # Массив объектов CategoriesModel для формирования меню категорий
            $categoriesQuery = CategoriesModel::find();
            $categoriesQuery->extendSelect(['id', 'name', 'seocode', 'active']);
            $categoriesQuery->with('subcategory');
            $categoriesQuery->asArray();
            $categoriesArray = $categoriesQuery->all();
            ArrayHelper::multisort($categoriesArray, 'name', SORT_ASC);
            self::$_instancesArray['categoriesList'] = $categoriesArray;
            
            # Массив объектов CurrencyModel для формирования формы замены валюты
            $currencyQuery = CurrencyModel::find();
            $currencyQuery->extendSelect(['id', 'code']);
            $currencyQuery->asArray();
            $currencyArray = $currencyQuery->all();
            $currencyArray = ArrayHelper::map($currencyArray, 'id', 'code');
            asort($currencyArray, SORT_STRING);
            self::$_instancesArray['currencyList'] = $currencyArray;
            
            return self::$_instancesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
