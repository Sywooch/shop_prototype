<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;
use app\models\ProductsModel;
use app\helpers\SessionHelper;

/**
 * Предоставляет функциональность для работы с фильтрами
 */
class FiltersHelper
{
    /**
     * Добавляет данные для фильтрации в \Yii::$app->filters
     * @return boolean
     */
    public static function addFilters()
    {
        try {
            \Yii::$app->filters->load(\Yii::$app->request->post());
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Добавляет данные раздела администрирования для фильтрации в \Yii::$app->filters
     * @return boolean
     */
    public static function addFiltersAdminCategories()
    {
        try {
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FOR_ADMIN_FILTER]);
            
            $productsModel->load(\Yii::$app->request->post());
            
             if (\Yii::$app->filters->categories != $productsModel->categories || \Yii::$app->filters->subcategory != $productsModel->subcategory) {
                self::cleanFilters();
            }
            
            \Yii::$app->filters->categories = $productsModel->categories;
            \Yii::$app->filters->subcategory = $productsModel->subcategory;
            
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Добавляет данные раздела администрирования для фильтрации в \Yii::$app->filters
     * @return boolean
     */
    public static function addFiltersConvert()
    {
        try {
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FOR_ADMIN_FILTER]);
            
            self::addFilters();
            
            $productsModel->load(\Yii::$app->request->post());
            
            \Yii::$app->filters->categories = $productsModel->categories;
            \Yii::$app->filters->subcategory = $productsModel->subcategory;
            
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет данные фильтров
     * @return boolean
     */
    public static function cleanFilters()
    {
        try {
            if (!\Yii::$app->filters->clean()) {
                throw new ErrorException('Ошибка при очистке фильтров!');
            }
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет данные фильтров, необходимых для построения URL
     * @return boolean
     */
    public static function cleanOtherFilters()
    {
        try {
            if (!\Yii::$app->filters->cleanOther()) {
                throw new ErrorException('Ошибка при очистке фильтров!');
            }
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
