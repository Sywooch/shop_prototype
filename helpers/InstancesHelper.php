<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;
use app\queries\GetCategoriesQuery;
use app\models\SearchModel;

/**
 * Коллекция методов для создания объектов,
 * вызываемых без изменений в разных блоках кода
 */
class InstancesHelper
{
    private static $_instancesArray = array();
    
    /**
     * Конструирует объекты для рендеринга
     * @return array
     */
    public static function getInstances()
    {
        try {
            # Массив объектов CategoriesModel для формирования меню категорий
            $categoriesQuery = new GetCategoriesQuery([
                'fields'=>['id', 'name', 'seocode'],
                'sorting'=>['name'=>SORT_ASC]
            ]);
            self::$_instancesArray['categoriesList'] = $categoriesQuery->getAll()->all();
            
            # Модель для формы поиска
            self::$_instancesArray['searchModel'] = new SearchModel(['scenario'=>SearchModel::GET_FROM_FORM]);
            
            return self::$_instancesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
