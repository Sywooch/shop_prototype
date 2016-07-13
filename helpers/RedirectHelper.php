<?php

namespace app\helpers;

/**
 * Коллекция методов для редиректа
 */
class RedirectHelper
{
    /**
     * Формирует URL для редиректа
     * @param object экземпляр модели, в свойствах которой содержатся необходимые методу данные
     * @return array массив элементов для формирования URI при помощи yii\helpers\Url
     */
    public static function getRedirectUrl($model)
    {
        try {
            if (empty(\Yii::$app->params['searchKey'])) {
                throw new ErrorException('Не установлена переменная searchKey!');
            }
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не установлена переменная categoryKey!');
            }
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не установлена переменная subCategoryKey!');
            }
            
            if (!empty($model->search)) {
                $urlArray = ['products-list/search', \Yii::$app->params['searchKey']=>$model->search];
            } else {
                $urlArray = ['products-list/index'];
                if (!empty($model->categories)) {
                    $urlArray = array_merge($urlArray, [\Yii::$app->params['categoryKey']=>$model->categories]);
                }
                if (!empty($model->subcategory)) {
                    $urlArray = array_merge($urlArray, [\Yii::$app->params['subCategoryKey']=>$model->subcategory]);
                }
            }
            return $urlArray;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
