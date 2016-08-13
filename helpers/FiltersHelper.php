<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;
use app\models\ProductsModel;

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
    public static function addFiltersAdmin()
    {
        try {
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_ADMIN_FILTER]);
            
            if (self::addFilters()) {
                $productsModel->load(\Yii::$app->request->post());
                if (!empty($productsModel->categories)) {
                    \Yii::$app->filters->categories = $productsModel->categories;
                }
                if (!empty($productsModel->subcategory)) {
                    \Yii::$app->filters->subcategory = $productsModel->subcategory;
                }
                \Yii::$app->filters->active = $productsModel->active;
            }
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
    
    /**
     * Удаляет данные фильтров административного раздела
     * @return boolean
     */
    public static function cleanAdminFilters()
    {
        try {
            if (!\Yii::$app->filters->cleanAdmin()) {
                throw new ErrorException('Ошибка при очистке фильтров!');
            }
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
