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
            self::$_instancesArray['categoriesList'] = $categoriesQuery->all();
            if (empty(self::$_instancesArray['categoriesList']) || !self::$_instancesArray['categoriesList'][0] instanceof CategoriesModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'CategoriesModel']));
            }
            ArrayHelper::multisort(self::$_instancesArray['categoriesList'], 'name', SORT_ASC);
            
            # Массив объектов CurrencyModel для формирования формы замены валюты
            $currencyQuery = CurrencyModel::find();
            $currencyQuery->extendSelect(['id', 'code']);
            self::$_instancesArray['currencyList'] = $currencyQuery->allMap('id', 'code');
            if (!is_array(self::$_instancesArray['currencyList']) || empty(self::$_instancesArray['currencyList'])) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array currencyList']));
            }
            asort(self::$_instancesArray['currencyList'], SORT_STRING);
            
            return self::$_instancesArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
